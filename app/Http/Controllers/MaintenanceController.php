<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\Appointment;
use App\Models\Asset;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->has('date') ? Carbon::parse($request->date) : Carbon::now();
        $startOfMonth = $date->copy()->startOfMonth();
        $endOfMonth = $date->copy()->endOfMonth();
        
        $currentMonthName = $date->format('F Y');
        $prevMonth = $date->copy()->subMonth()->format('Y-m-d');
        $nextMonth = $date->copy()->addMonth()->format('Y-m-d');
        $iconMaintenance = '<svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path></svg>';
        $iconCalendar = '<svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>';
        $iconMeeting = '<svg class="w-3 h-3 mr-1 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>';

        $maintenances = MaintenanceSchedule::with('asset')
            ->whereBetween('scheduled_date', [$startOfMonth, $endOfMonth])
            ->get();

        $appointments = Appointment::whereBetween('date', [$startOfMonth, $endOfMonth])
            ->get();

        $events = [];

        foreach ($maintenances as $m) {
            $day = Carbon::parse($m->scheduled_date)->day;
            $events[$day][] = [
                'id' => $m->id,
                'title' => $m->asset ? $m->asset->name : 'Unknown Asset', // Judul bersih tanpa emoji
                'icon' => $iconMaintenance, // Kirim SVG
                'type' => 'maintenance',
                'color_class' => 'bg-red-50 text-red-700 border border-red-200'
            ];
        }

        foreach ($appointments as $a) {
            $day = Carbon::parse($a->date)->day;

            $typeData = match($a->type ?? 'appointment') {
                'meeting' => [
                    'icon' => $iconMeeting, 
                    'class' => 'bg-emerald-50 text-emerald-700 border border-emerald-200'
                ],
                default => [
                    'icon' => $iconCalendar, 
                    'class' => 'bg-blue-50 text-blue-700 border border-blue-200'
                ],
            };

            $events[$day][] = [
                'id' => $a->id,
                'title' => $a->title, 
                'icon' => $typeData['icon'], 
                'type' => 'appointment',
                'color_class' => $typeData['class']
            ];
        }

        $daysInMonth = $startOfMonth->daysInMonth;
        $firstDayOfWeek = $startOfMonth->dayOfWeek; 
        $totalCells = $firstDayOfWeek + $daysInMonth;
        $remainingCells = ($totalCells % 7 === 0) ? 0 : (7 - ($totalCells % 7));

        $calendarDays = [];
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDayDate = $startOfMonth->copy()->day($day);
            $isToday = $currentDayDate->isToday();
            $isWeekend = $currentDayDate->isWeekend();
            
            $numberColor = $isToday ? 'bg-indigo-600 text-white shadow-md' : ($isWeekend ? 'text-red-500' : 'text-slate-700');
            $bgColor = $isWeekend ? 'bg-red-50/20' : 'bg-white';

            $calendarDays[] = [
                'day' => $day,
                'full_date' => $currentDayDate->format('Y-m-d'),
                'bg_class' => $bgColor,
                'number_class' => $numberColor,
                'events' => $events[$day] ?? [] 
            ];
        }

        $assets = Asset::all(); 

        return view('it_maintenance', compact(
            'currentMonthName', 'prevMonth', 'nextMonth', 
            'firstDayOfWeek', 'remainingCells', 'calendarDays', 'assets'
        ));
    }

    public function store(Request $request)
    {
        // Validasi
        $request->validate([
            'date' => 'required|date',
            'type' => 'required|in:maintenance,appointment,meeting',
            'title' => 'required_if:type,appointment,meeting',
        ]);

        if ($request->type === 'maintenance') {
            $request->validate(['asset_id' => 'required|exists:assets,id']);
            MaintenanceSchedule::create([
                'asset_id' => $request->asset_id,
                'technician_id' => Auth::id(),
                'scheduled_date' => $request->date,
                'status' => 'scheduled'
            ]);
        } else {
            Appointment::create([
                'title' => $request->title,
                'date' => $request->date,
                'type' => $request->type,
                'user_id' => Auth::id()
            ]);
        }

        return back()->with('success', 'Jadwal berhasil disimpan.');
    }

    public function destroy($id)
    {
        $appt = Appointment::where('user_id', Auth::id())->findOrFail($id);
        $appt->delete();
        return back()->with('success', 'Jadwal dihapus.');
    }
}