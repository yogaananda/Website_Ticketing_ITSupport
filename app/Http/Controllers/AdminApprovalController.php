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
        
        $procurements->transform(function($item) {
            $item->prioColor = match($item->priority) {
                'critical' => 'bg-red-100 text-red-800 border-red-200',
                'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                'low' => 'bg-gray-100 text-gray-800 border-gray-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200',
            };
            return $item;
        });

        $historyProcurements = Procurement::with(['user', 'ticket'])
                        ->whereIn('status', ['approved', 'rejected'])
                        ->orderBy('updated_at', 'desc')
                        ->get();
                        
        $historyProcurements->transform(function($item) {
            $item->prioColor = match($item->priority) {
                'critical' => 'bg-red-100 text-red-800 border-red-200',
                'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                'low' => 'bg-gray-100 text-gray-800 border-gray-200',
                default => 'bg-gray-100 text-gray-800 border-gray-200',
            };
            return $item;
        });

        return view('admin_approvals', compact('assetLoans', 'consumableRequests', 'procurements', 'historyProcurements'));
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
