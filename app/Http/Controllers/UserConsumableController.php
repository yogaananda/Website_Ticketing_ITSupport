<?php

namespace App\Http\Controllers;

use App\Models\Consumable;
use App\Models\ConsumableRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserConsumableController extends Controller
{
    public function index()
    {
        $myRequests = ConsumableRequest::with('consumable')
                        ->where('user_id', Auth::id())
                        ->latest()
                        ->paginate(10);

        $consumables = Consumable::where('stock', '>', 0)->get();

        return view('user_consumables', compact('myRequests', 'consumables'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'consumable_id' => 'required|exists:consumables,id',
            'amount'        => 'required|integer|min:1',
            'reason'        => 'nullable|string|max:255', 
        ]);

        $item = Consumable::findOrFail($request->consumable_id);
        if ($request->amount > $item->stock) {
            return redirect()->back()->with('error', 'Jumlah permintaan melebihi stok tersedia!');
        }

        ConsumableRequest::create([
            'user_id'       => Auth::id(),
            'consumable_id' => $request->consumable_id,
            'amount'        => $request->amount,
            'status'        => 'pending',
            'reason'        => $request->reason,
        ]);

        return redirect()->back()->with('success', 'Permintaan barang berhasil dikirim.');
    }
}