<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Consumable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ItApprovalController extends Controller
{
    public function index()
    {
        $assetLoans = AssetLoan::with(['user', 'asset'])
            ->orderBy('created_at', 'desc')
            ->get();

        $consumableRequests = ConsumableRequest::with(['user', 'consumable'])
            ->orderBy('created_at', 'desc')
            ->get();

        // VIEW KHUSUS IT
        return view('it_approvals', compact(
            'assetLoans',
            'consumableRequests'
        ));
    }

    public function approveAsset($id)
    {
        $loan = AssetLoan::findOrFail($id);

        $loan->update([
            'status' => 'active',
            'admin_id' => Auth::id()
        ]);

        $loan->asset->update([
            'status' => 'in_use',
            'user_id' => $loan->user_id
        ]);

        return back()->with('success', 'Aset disetujui');
    }

    public function rejectAsset($id)
    {
        $loan = AssetLoan::findOrFail($id);

        $loan->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Aset ditolak');
    }

    public function returnAsset($id)
    {
        $loan = AssetLoan::findOrFail($id);

        $loan->update([
            'status' => 'returned',
            'return_date' => Carbon::now()
        ]);

        $loan->asset->update([
            'status' => 'ready',
            'user_id' => null
        ]);

        return back()->with('success', 'Aset dikembalikan');
    }

    public function approveConsumable($id)
    {
        $req = ConsumableRequest::findOrFail($id);
        $item = Consumable::findOrFail($req->consumable_id);

        if ($item->stock < $req->amount) {
            return back()->with('error', 'Stock tidak cukup');
        }

        $item->decrement('stock', $req->amount);

        $req->update([
            'status' => 'approved',
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Consumable disetujui');
    }

    public function rejectConsumable($id)
    {
        $req = ConsumableRequest::findOrFail($id);

        $req->update([
            'status' => 'rejected',
            'admin_id' => Auth::id()
        ]);

        return back()->with('success', 'Consumable ditolak');
    }
}
