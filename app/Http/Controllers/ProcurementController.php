<?php

namespace App\Http\Controllers;

use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProcurementController extends Controller
{
    public function index()
    {
        $procurements = Procurement::with('user')->latest()->paginate(10);
        
        return view('it_procurement', compact('procurements'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'item_name'       => 'required|string|max:255',
            'quantity'        => 'required|integer|min:1',
            'estimated_price' => 'nullable|numeric|min:0',
            'priority'        => 'required|in:low,medium,high,critical',
            'link_reference'  => 'nullable|url',
            'description'     => 'nullable|string',
            'ticket_id'       => 'nullable|exists:tickets,id',
        ]);

        Procurement::create([
            'user_id'         => Auth::id(),
            'ticket_id'       => $request->ticket_id,
            'item_name'       => $request->item_name,
            'quantity'        => $request->quantity,
            'estimated_price' => $request->estimated_price,
            'priority'        => $request->priority,
            'link_reference'  => $request->link_reference,
            'description'     => $request->description,
            'status'          => 'pending', 
        ]);

        return redirect()->route('it.procurements.index')->with('success', 'Pengajuan pembelian berhasil dibuat!');
    }
}