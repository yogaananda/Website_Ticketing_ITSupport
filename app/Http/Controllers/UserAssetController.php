<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAssetController extends Controller
{
    public function index()
    {
        $myLoans = AssetLoan::with('asset')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        $myLoans->transform(function($loan) {
            $loan->statusColor = match($loan->status) {
                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                'active' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                'returned' => 'bg-gray-100 text-gray-800 border-gray-200',
                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                'overdue' => 'bg-red-100 text-red-800 border-red-200 font-bold',
                default => 'bg-gray-100 text-gray-800'
            };

            $loan->statusLabel = match($loan->status) {
                'pending' => 'Menunggu Approval',
                'active' => 'Aktif Dipinjam',
                'returned' => 'Selesai Dikembalikan',
                'rejected' => 'Ditolak',
                'overdue' => 'Terlambat Dikembalikan!',
                default => ucfirst($loan->status)
            };
            return $loan;
        });

        $availableAssets = Asset::where('status', 'ready')
                          ->where('condition', 'good')
                          ->get();

        return view('user_assets', compact('myLoans', 'availableAssets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'asset_id'  => 'required|exists:assets,id',
            'loan_date' => 'required|date',
            'due_date'  => 'required|date|after_or_equal:loan_date',
            'notes'     => 'required|string|max:255',
        ]);

        AssetLoan::create([
            'user_id'   => Auth::id(),
            'asset_id'  => $request->asset_id,
            'loan_date' => $request->loan_date,
            'due_date'  => $request->due_date,
            'status'    => 'pending',
            'notes'     => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim!');
    }
}