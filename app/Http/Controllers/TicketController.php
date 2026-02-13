<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'priority' => 'required|in:low,medium,high',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('ticket_images', 'public');
        }

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'priority' => $request->priority,
            'status' => 'open',
            'image' => $imagePath,
            'ticket_code' => 'TKT-' . date('YmdHis'),
            'queue_number' => Ticket::whereDate('created_at', now())->count() + 1,
        ]);

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => 'Laporan berhasil dibuat. Menunggu antrean teknisi.',
        ]);

        return redirect()->route('user.dashboard')->with('success', 'Laporan berhasil dikirim!');
    }

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);
        $currentUserId = Auth::id();

        if ($ticket->assigned_to && $ticket->assigned_to !== $currentUserId) {
            return back()->with('error', 'Maaf, tiket ini sudah ditangani oleh teknisi lain.');
        }

        $request->validate([
            'action_type' => 'required|in:progress,resolved,cancel',
            'note' => 'nullable|string' 
        ]);

        $statusMessage = '';
        $note = $request->note;

        if ($request->action_type == 'progress') {
            $ticket->status = 'in_progress';
            $ticket->assigned_to = $currentUserId;
            $msg = $note ? $note : 'Melakukan pengecekan / update progres.';
            $statusMessage = 'PROGRES: ' . $msg;
        }
        elseif ($request->action_type == 'resolved') {
            $ticket->status = 'resolved';
            $msg = $note ? $note : 'Masalah telah diselesaikan dengan baik.';
            $statusMessage = 'SELESAI: ' . $msg;
        }
        elseif ($request->action_type == 'cancel') {
            $ticket->status = 'open';
            $ticket->assigned_to = null;
            $msg = $note ? $note : 'Tiket dikembalikan ke status Open.';
            $statusMessage = 'DIKEMBALIKAN: ' . $msg;
        }

        $ticket->save();

        Comment::create([
            'ticket_id' => $ticket->id,
            'user_id'   => $currentUserId,
            'message'   => $statusMessage
        ]);

        return back()->with('success', 'Status tiket berhasil diperbarui!');
    }
    
}