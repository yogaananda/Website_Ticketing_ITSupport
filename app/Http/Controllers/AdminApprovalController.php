<?php

namespace App\Http\Controllers;

use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Consumable;
use App\Models\Procurement; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class AdminApprovalController extends Controller
{
    public function index()
    {
        $assetLoans = AssetLoan::with(['user', 'asset'])
                        ->orderBy('created_at', 'desc')
                        ->get();
        $consumableRequests = ConsumableRequest::with(['user', 'consumable'])
                                ->orderBy('created_at', 'desc')
                                ->get();
        $procurements = Procurement::with(['user', 'ticket'])
                        ->where('status', 'pending')
                        ->orderByRaw("FIELD(priority, 'critical', 'high', 'medium', 'low')")
                        ->get();
        return view('admin_approvals', compact('assetLoans', 'consumableRequests', 'procurements'));
    }
    public function approveProcurement($id)
    {
        $procurement = Procurement::findOrFail($id);
        
        $procurement->update([
            'status' => 'approved',
        ]);

        return redirect()->back()->with('success', 'Pengajuan pembelian DISETUJUI.');
    }

    public function rejectProcurement(Request $request, $id)
    {
        $procurement = Procurement::findOrFail($id);
        
        $procurement->update([
            'status' => 'rejected',
            'admin_note' => $request->admin_note
        ]);

        return redirect()->back()->with('error', 'Pengajuan pembelian DITOLAK.');
    }
}
