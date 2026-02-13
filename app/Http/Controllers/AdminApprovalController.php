<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Consumable;
use App\Models\Procurement; // <--- TAMBAHAN: Import Model Procurement
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AdminApprovalController extends Controller
{
    public function index()
    {
        // A. Ambil data peminjaman aset (Aset Fisik)
        $assetLoans = AssetLoan::with(['user', 'asset'])
                        ->orderBy('created_at', 'desc')
                        ->get();

        // B. Ambil data permintaan barang habis pakai (Logistik)
        $consumableRequests = ConsumableRequest::with(['user', 'consumable'])
                                ->orderBy('created_at', 'desc')
                                ->get();

        // C. TAMBAHAN: Ambil data request pembelian (Procurement)
        // Kita ambil yang statusnya pending untuk prioritas approval
        $procurements = Procurement::with(['user', 'ticket'])
                        ->where('status', 'pending')
                        ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
                        ->get();

        // Kirim ke view (Note: Pastikan view Anda bisa menghandle 3 variabel ini)
        return view('admin_approvals', compact('assetLoans', 'consumableRequests', 'procurements'));
        // Catatan: Jika view Anda bernama 'it_approvals', ubah 'admin_approvals' menjadi 'it_approvals'
    }
    public function approveProcurement($id)
    {
        $procurement = Procurement::findOrFail($id);
        
        $procurement->update([
            'status' => 'approved',
            // Jika ada kolom approved_by di tabel procurements, uncomment baris bawah:
            // 'approved_by' => Auth::id() 
        ]);

        return redirect()->back()->with('success', 'Pengajuan pembelian DISETUJUI.');
    }

    public function rejectProcurement(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        
        $procurement->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note // Alasan penolakan dari modal
        ]);

        return redirect()->back()->with('error', 'Pengajuan pembelian DITOLAK.');
    }
}
