@extends('layouts.layout')

@section('title', 'Jadwal Maintenance')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">
    @php
    use Carbon\Carbon;

    // KONFIGURASI BULAN & TAHUN (Bisa diganti dinamis dari Controller)
    $year = 2026;
    $month = 3; // Maret

    // Membuat objek tanggal
    $startOfMonth = Carbon::createFromDate($year, $month, 1);
    $daysInMonth = $startOfMonth->daysInMonth;
    
    // Menentukan hari pertama jatuh di index ke berapa (0=Minggu, 1=Senin, ..., 6=Sabtu)
    // Sesuai kalender dinding umum: Minggu adalah kolom pertama.
    $firstDayOfWeek = $startOfMonth->dayOfWeek; 

    // DATA HARI LIBUR (REAL WORLD DATA untuk Maret 2026)
    $holidays = [
        19 => ['name' => 'Hari Suci Nyepi (Tahun Baru Saka)', 'color' => 'red'],
        20 => ['name' => 'Cuti Bersama Nyepi', 'color' => 'orange'], // Biasanya ada cuti bersama
        21 => ['name' => 'Hari Raya Idul Fitri 1447H', 'color' => 'green'],
        22 => ['name' => 'Cuti Bersama Idul Fitri', 'color' => 'green'],
    ];
@endphp

<div class="max-w-7xl mx-auto p-6 font-sans">
    
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        <div class="flex flex-col md:flex-row items-center justify-between p-6 bg-white border-b border-gray-100">
            <div class="mb-4 md:mb-0 text-center md:text-left">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Calendar Daily Activity</span>
                <h2 class="text-3xl font-extrabold text-slate-800 mt-1">
                    {{ $startOfMonth->format('F Y') }}
                </h2>
            </div>
            
            <div class="flex items-center space-x-2">
                <button class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-2 focus:ring-slate-200">
                    Today
                </button>
                <div class="inline-flex rounded-md shadow-sm" role="group">
                    <button type="button" class="p-2 text-sm font-medium text-slate-600 bg-white border border-gray-300 rounded-l-lg hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    </button>
                    <button type="button" class="p-2 text-sm font-medium text-slate-600 bg-white border-l-0 border border-gray-300 rounded-r-lg hover:bg-gray-50">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50">
            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $index => $day)
                <div class="py-3 text-center text-sm font-semibold tracking-wide border-r border-gray-200 last:border-r-0
                    {{ $index === 0 || $index === 6 ? 'text-red-500 bg-red-50/50' : 'text-slate-500' }}">
                    {{ $day }}
                </div>
            @endforeach
        </div>

        <div class="grid grid-cols-7 bg-white">
            
            {{-- 1. EMPTY CELLS: Kotak kosong sebelum tanggal 1 --}}
            @for ($i = 0; $i < $firstDayOfWeek; $i++)
                <div class="min-h-[120px] bg-gray-50/30 border-b border-r border-gray-100 last:border-r-0"></div>
            @endfor

            {{-- 2. ACTUAL DAYS: Tanggal 1 sampai akhir bulan --}}
            @for ($day = 1; $day <= $daysInMonth; $day++)
                @php
                    // Hitung index kolom (0=Minggu ... 6=Sabtu)
                    // Logic: (IndexHariPertama + Tanggal - 1) % 7
                    $currentDayOfWeek = ($firstDayOfWeek + $day - 1) % 7;
                    $isWeekend = ($currentDayOfWeek === 0 || $currentDayOfWeek === 6);
                    
                    // Cek Hari Libur
                    $holiday = $holidays[$day] ?? null;
                @endphp

                <div class="group relative min-h-[120px] p-2 border-b border-r border-gray-100 last:border-r-0 hover:bg-slate-50 transition-colors 
                    {{ $isWeekend ? 'bg-red-50/20' : 'bg-white' }}">
                    
                    <div class="flex justify-between items-start">
                        <span class="text-sm font-semibold w-7 h-7 flex items-center justify-center rounded-full 
                            {{ $isWeekend ? 'text-red-500' : 'text-slate-700' }}
                            {{-- Contoh styling 'Hari Ini' (misal tgl 13) --}}
                            {{ $day == 13 ? 'bg-blue-600 text-white shadow-md' : '' }}
                        ">
                            {{ $day }}
                        </span>
                        
                        <button class="opacity-0 group-hover:opacity-100 text-slate-400 hover:text-blue-600 transition-opacity">
                           <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </button>
                    </div>

                    <div class="mt-2 space-y-1">
                        @if($holiday)
                            <div class="px-2 py-1 text-[10px] font-medium leading-tight rounded border truncate cursor-help group-hover:whitespace-normal group-hover:absolute group-hover:z-10 group-hover:w-full group-hover:left-0
                                {{ $holiday['color'] === 'red' ? 'text-red-700 bg-red-100 border-red-200' : '' }}
                                {{ $holiday['color'] === 'green' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : '' }}
                                {{ $holiday['color'] === 'orange' ? 'text-orange-700 bg-orange-100 border-orange-200' : '' }}
                            ">
                                {{ $holiday['name'] }}
                            </div>
                        @endif

                        {{-- Contoh Event Meeting Dummy --}}
                        @if($day == 10)
                            <div class="px-2 py-1 text-[10px] font-medium text-blue-700 bg-blue-50 border border-blue-100 rounded truncate">
                                09:00 Daily Scrum
                            </div>
                        @endif
                    </div>
                </div>
            @endfor

            {{-- 3. FILLING: Sisa kotak kosong di akhir bulan agar grid rapi --}}
            @php
                $totalCellsFilled = $firstDayOfWeek + $daysInMonth;
                $remainingCells = 7 - ($totalCellsFilled % 7);
                if ($remainingCells == 7) $remainingCells = 0;
            @endphp

            @for ($i = 0; $i < $remainingCells; $i++)
                <div class="min-h-[120px] bg-gray-50/30 border-b border-r border-gray-100 last:border-r-0"></div>
            @endfor

        </div>
        
        <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-xs text-gray-500">
            Showing events for {{ $startOfMonth->format('F Y') }} • Timezone: Asia/Jakarta
        </div>
    </div>
</div>


</div>



@endsection