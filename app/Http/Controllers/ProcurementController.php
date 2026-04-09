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
        
        $procurements->getCollection()->transform(function($item) {
            $item->prioColor = match($item->priority) {
                'critical' => 'bg-red-100 text-red-800 border-red-200',
                'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                'low' => 'bg-gray-100 text-gray-800 border-gray-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200'
            };

            $item->prioLabel = match($item->priority) {
                'low' => 'Low Priority',
                'medium' => 'Medium Priority',
                'high' => 'High Priority',
                'critical' => 'Critical',
                default => ucfirst($item->priority),
            };

            $item->statusColor = match($item->status) {
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'approved' => 'bg-teal-100 text-teal-800 border-teal-200',
                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                'purchased' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200'
            };

            $item->statusIcon = match($item->status) {
                'pending' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                'approved' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                'rejected' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                'purchased' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
                default => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>'
            };
            
            return $item;
        });

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