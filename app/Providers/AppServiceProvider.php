<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Ticket;
use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Procurement;

class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Bagikan data ke semua view yang menggunakan layouts.layout
        View::composer('layouts.layout', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $role = $user->role;

                // 1. Hitung Notifikasi Admin
                $pendingApprovals = 0;
                $pendingProcurements = 0;
                if ($role === 'admin') {
                    $pendingApprovals = AssetLoan::where('status', 'pending')->count() + 
                                       ConsumableRequest::where('status', 'pending')->count();
                    $pendingProcurements = Procurement::where('status', 'pending')->count();
                }

                // 2. Hitung Notifikasi IT Support
                $openTickets = 0;
                if ($role === 'it_support' || $role === 'admin') {
                    $openTickets = Ticket::where('status', 'open')->count();
                }

                // Kirim variabel ke Layout agar tidak "Undefined"
                $view->with([
                    'pendingApprovals' => $pendingApprovals,
                    'pendingProcurements' => $pendingProcurements,
                    'openTickets' => $openTickets,
                    'role' => $role
                ]);
            }
        });
    }
}