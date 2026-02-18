<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use App\Models\AssetLoan;
use App\Models\ConsumableRequest;
use App\Models\Procurement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function user()
    {
        $userId = Auth::id();
        $role = Auth::user()->role;
        $globalData = $this->getGlobalNotifs($role);

        $tickets = Ticket::where('user_id', $userId)->with(['technician', 'category'])->latest()->paginate(5);
        $activeTickets = Ticket::where('user_id', $userId)->whereIn('status', ['open', 'in_progress'])->count();
        $resolvedTickets = Ticket::where('user_id', $userId)->whereIn('status', ['resolved', 'closed'])->count();
        $globalQueue = Ticket::where('status', 'open')->count();

        return view('user_dashboard', array_merge(
            compact('tickets', 'activeTickets', 'resolvedTickets', 'globalQueue'),
            $globalData
        ));
    }
    
    public function it(Request $request)
    {
        $query = Ticket::with(['user', 'category', 'technician'])->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('ticket_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function($subQ) use ($search) {
                      $subQ->where('full_name', 'like', "%{$search}%")
                           ->orWhere('username', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->filled('status')) {
            if ($request->status == 'high') {
                $query->where('priority', 'high');
            } elseif ($request->status == 'open') {
                $query->where('status', 'open');
            } elseif ($request->status == 'on_progress') {
                $query->where('status', 'in_progress');
            }
        }

        $tickets = $query->paginate(10)->withQueryString();

        $urgent = Ticket::where('priority', 'high')->whereIn('status', ['open', 'in_progress'])->count();
        $newTickets = Ticket::where('status', 'open')->count();
        $onProgress = Ticket::where('status', 'in_progress')->count();
        $completedToday = Ticket::where('status', 'resolved')->whereDate('updated_at', Carbon::today())->count();

        return view('it_dashboard', compact('tickets', 'urgent', 'newTickets', 'onProgress', 'completedToday'));
    }

    public function admin(Request $request)
    {
        $users = User::withCount(['it_tickets' => function ($q) {
            $q->whereIn('status', ['open', 'in_progress']); 
        }])->latest()->paginate(10);

        $totalTicketsMonth = Ticket::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count();
        $workingOnIt = Ticket::where('status', 'in_progress')->count();
        $resolved = Ticket::whereIn('status', ['resolved', 'closed'])->count();
        $unhandled = Ticket::where('status', 'open')->count();

        $ongoingTickets = Ticket::where('status', 'in_progress')->with(['user', 'technician'])->latest()->take(5)->get();
        
        $query = Ticket::whereIn('status', ['resolved', 'closed'])->with(['user', 'technician'])->latest();
        $historyTickets = $query->paginate(5, ['*'], 'history_page')->withQueryString();

        $weeklyLabels = [];
        $completedData = []; 
        $pendingData = [];   

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $weeklyLabels[] = $date->format('D');
            $completedData[] = Ticket::whereDate('created_at', $date)->whereIn('status', ['resolved', 'closed'])->count();
            $pendingData[] = Ticket::whereDate('created_at', $date)->whereIn('status', ['open', 'in_progress'])->count();
        }
        
        $yearlyLabels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $yearlyCompleted = [];
        $yearlyPending = [];

        for ($m = 1; $m <= 12; $m++) {
            $yearlyCompleted[] = Ticket::whereYear('created_at', date('Y'))->whereMonth('created_at', $m)->whereIn('status', ['resolved', 'closed'])->count();
            $yearlyPending[] = Ticket::whereYear('created_at', date('Y'))->whereMonth('created_at', $m)->whereIn('status', ['open', 'in_progress'])->count();
        }

        $resolvedWeekly = array_sum($completedData);
        $pendingWeekly = array_sum($pendingData);
        $totalWeekly = $resolvedWeekly + $pendingWeekly;

        return view('admin_dashboard', compact(
            'users', 
            'totalTicketsMonth', 'workingOnIt', 'resolved', 'unhandled', 
            'ongoingTickets', 'historyTickets',
            'weeklyLabels', 'completedData', 'pendingData',
            'yearlyLabels', 'yearlyCompleted', 'yearlyPending',
            'totalWeekly', 'resolvedWeekly', 'pendingWeekly'
        ));
    }

    public function report(Request $request)
    {
        $query = Ticket::with(['user', 'category', 'technician'])->latest();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('ticket_code', 'like', "%{$request->search}%");
            });
        }

        if ($request->start_date && $request->end_date) {
            $query->whereBetween('created_at', [
                $request->start_date . ' 00:00:00', 
                $request->end_date . ' 23:59:59'
            ]);
        }

        $tickets = $query->paginate(10)->withQueryString();

        return view('admin_report', compact('tickets'));
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'full_name' => 'required|string|max:255',
            'username'  => 'required|unique:users,username,' . $id,
            'email'     => 'required|email|unique:users,email,' . $id,
            'division'  => 'required|string',
            'role'      => 'required|in:user,it_support,admin',
        ]);

        $user->update([
            'full_name' => $request->full_name,
            'username'  => $request->username,
            'email'     => $request->email,
            'division'  => $request->division,
            'role'      => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => bcrypt($request->password)]);
        }

        return back()->with('success', 'Data user berhasil diperbarui!');
    }
    private function getGlobalNotifs($role)
    {
        $notifs = [
            'pendingApprovals' => 0,
            'openTickets' => 0,
            'pendingProcurements' => 0
        ];

        if ($role === 'admin') {
            $pendingAssets = AssetLoan::where('status', 'pending')->count();
            $pendingConsumables = ConsumableRequest::where('status', 'pending')->count();
            $notifs['pendingApprovals'] = $pendingAssets + $pendingConsumables;
            $notifs['pendingProcurements'] = Procurement::where('status', 'pending')->count();
        }

        if ($role === 'it_support' || $role === 'admin') {
            $notifs['openTickets'] = Ticket::where('status', 'open')->count();
        }

        return $notifs;
    }
}