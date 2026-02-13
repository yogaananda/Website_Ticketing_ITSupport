<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAssetController extends Controller
{
    // 1. TAMPILKAN HALAMAN (Index)
    public function index()
    {
        // Ambil data peminjaman milik user yang login
        $myLoans = AssetLoan::with('asset')
                    ->where('user_id', Auth::id())
                    ->latest()
                    ->get();

        // Ambil aset yang statusnya 'ready' untuk isi dropdown modal
        $availableAssets = Asset::where('status', 'ready')
                          ->where('condition', 'good')
                          ->get();

        // PENTING: Ini memanggil file 'resources/views/user_assets.blade.php'
        return view('user_assets', compact('myLoans', 'availableAssets'));
    }

    // 2. PROSES PENGAJUAN (Store)
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