@extends('layouts.layout')

@section('title', 'Riwayat Pengerjaan')

@section('content')
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800 dark:text-neutral-200 flex items-center gap-2">
                <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                </svg>
                Riwayat Pengerjaan Saya
            </h1>
            <p class="mt-1 text-gray-600 dark:text-neutral-400">
                Rekap semua tiket yang telah Anda selesaikan — <span class="font-semibold text-indigo-600">{{ Auth::user()->full_name ?? Auth::user()->username }}</span>
            </p>
        </div>
        <a href="{{ route('it.dashboard') }}"
           class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 rounded-lg px-4 py-2 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Kembali ke Dashboard
        </a>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="flex items-center p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-3 mr-4 text-indigo-600 bg-indigo-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <div>
                <p class="mb-0.5 text-sm font-medium text-gray-500">Total Diselesaikan</p>
                <p class="text-3xl font-bold text-gray-900">{{ $totalDone }}</p>
            </div>
        </div>

        <div class="flex items-center p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-3 mr-4 text-green-600 bg-green-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            </div>
            <div>
                <p class="mb-0.5 text-sm font-medium text-gray-500">Selesai Hari Ini</p>
                <p class="text-3xl font-bold text-gray-900">{{ $doneToday }}</p>
            </div>
        </div>

        <div class="flex items-center p-5 bg-white border border-gray-200 rounded-xl shadow-sm hover:shadow-md transition-shadow">
            <div class="p-3 mr-4 text-blue-600 bg-blue-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            </div>
            <div>
                <p class="mb-0.5 text-sm font-medium text-gray-500">Selesai Bulan Ini</p>
                <p class="text-3xl font-bold text-gray-900">{{ $doneThisMonth }}</p>
            </div>
        </div>
    </div>

    {{-- Table Card --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        {{-- Toolbar --}}
        <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4 border-b border-gray-100">
            <h2 class="text-base font-semibold text-gray-700">Daftar Tiket Selesai</h2>
            <form action="{{ route('it.history') }}" method="GET" class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                </div>
                <input type="text" name="search" id="history-search" value="{{ request('search') }}"
                       class="block w-full ps-10 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500 placeholder-gray-400"
                       placeholder="Cari ID, Masalah, Pelapor...">
            </form>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-700">
                <thead class="text-xs font-semibold text-gray-500 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-5 py-3">ID Tiket</th>
                        <th class="px-5 py-3">Masalah & Kategori</th>
                        <th class="px-5 py-3">Pelapor</th>
                        <th class="px-5 py-3">Mulai Pengerjaan</th>
                        <th class="px-5 py-3">Selesai Pengerjaan</th>
                        <th class="px-5 py-3 text-center">Total Waktu</th>
                        <th class="px-5 py-3 text-center">Prioritas</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($historyTickets as $ticket)
                    @php
                        // Hitung durasi pengerjaan
                        $start    = $ticket->started_at;
                        $end      = $ticket->resolved_at;
                        $duration = '—';
                        if ($start && $end) {
                            $diffMins  = $start->diffInMinutes($end);
                            $hours     = intdiv($diffMins, 60);
                            $mins      = $diffMins % 60;
                            $duration  = ($hours > 0 ? "{$hours} jam " : '') . "{$mins} menit";
                        }
                    @endphp
                    <tr class="hover:bg-indigo-50/40 transition-colors">
                        {{-- ID Tiket --}}
                        <td class="px-5 py-4">
                            <span class="inline-flex items-center gap-1.5 font-mono font-semibold text-indigo-700 bg-indigo-50 border border-indigo-200 rounded px-2 py-0.5 text-xs">
                                #{{ $ticket->ticket_code }}
                            </span>
                        </td>

                        {{-- Masalah & Kategori --}}
                        <td class="px-5 py-4 max-w-[200px]">
                            <div class="font-semibold text-gray-800 truncate" title="{{ $ticket->title }}">
                                {{ Str::limit($ticket->title, 30) }}
                            </div>
                            <div class="mt-1">
                                <span class="inline-flex items-center gap-1 text-xs text-indigo-600 bg-indigo-50 border border-indigo-100 rounded-full px-2 py-0.5">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                                    {{ $ticket->category->name ?? '—' }}
                                </span>
                            </div>
                        </td>

                        {{-- Pelapor --}}
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-400 to-purple-400 flex items-center justify-center text-white text-xs font-bold shrink-0">
                                    {{ strtoupper(substr($ticket->user->full_name ?? $ticket->user->username ?? 'U', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-xs leading-tight">{{ $ticket->user->full_name ?? $ticket->user->username ?? '—' }}</p>
                                    @if($ticket->user->division ?? null)
                                        <p class="text-[10px] text-gray-400">{{ $ticket->user->division }}</p>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Mulai Pengerjaan --}}
                        <td class="px-5 py-4">
                            @if ($ticket->started_at)
                                <div class="text-gray-800 font-medium">{{ $ticket->started_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $ticket->started_at->format('H:i') }} WIB</div>
                            @else
                                <span class="text-gray-400 text-xs italic">Tidak tercatat</span>
                            @endif
                        </td>

                        {{-- Selesai Pengerjaan --}}
                        <td class="px-5 py-4">
                            @if ($ticket->resolved_at)
                                <div class="text-gray-800 font-medium">{{ $ticket->resolved_at->format('d M Y') }}</div>
                                <div class="text-xs text-gray-400">{{ $ticket->resolved_at->format('H:i') }} WIB</div>
                            @else
                                <span class="text-gray-400 text-xs italic">Tidak tercatat</span>
                            @endif
                        </td>

                        {{-- Total Waktu --}}
                        <td class="px-5 py-4 text-center">
                            @if ($start && $end)
                                @php
                                    $diffMins2 = $start->diffInMinutes($end);
                                @endphp
                                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-semibold
                                    {{ $diffMins2 <= 30 ? 'bg-green-100 text-green-700 border border-green-200' : ($diffMins2 <= 120 ? 'bg-yellow-100 text-yellow-700 border border-yellow-200' : 'bg-red-100 text-red-700 border border-red-200') }}">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $duration }}
                                </span>
                            @else
                                <span class="text-gray-400 text-xs">—</span>
                            @endif
                        </td>

                        {{-- Prioritas --}}
                        <td class="px-5 py-4 text-center">
                            @if ($ticket->priority == 'high')
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-0.5 rounded border border-red-300">High</span>
                            @elseif ($ticket->priority == 'medium')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-0.5 rounded border border-yellow-300">Medium</span>
                            @else
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-0.5 rounded border border-green-300">Low</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-5 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <svg class="w-14 h-14 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                <p class="font-medium text-gray-500">Belum ada tiket yang diselesaikan</p>
                                <p class="text-sm">Selesaikan tiket dari dashboard untuk melihat riwayat di sini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                Showing <span class="font-semibold text-gray-800">{{ $historyTickets->firstItem() ?? 0 }}</span>
                to <span class="font-semibold text-gray-800">{{ $historyTickets->lastItem() ?? 0 }}</span>
                of <span class="font-semibold text-gray-800">{{ $historyTickets->total() }}</span> tiket
            </div>

            @if ($historyTickets->hasPages())
            <nav aria-label="Pagination">
                <ul class="flex -space-x-px text-sm">
                    <li>
                        @if ($historyTickets->onFirstPage())
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 rounded-s-lg px-3 h-9 cursor-not-allowed select-none">Prev</span>
                        @else
                            <a href="{{ $historyTickets->appends(request()->query())->previousPageUrl() }}" class="flex items-center justify-center text-gray-600 bg-white border border-gray-300 hover:bg-indigo-50 hover:text-indigo-600 px-3 h-9 rounded-s-lg transition-all">Prev</a>
                        @endif
                    </li>
                    @foreach (range(1, $historyTickets->lastPage()) as $i)
                        <li>
                            @if ($i == $historyTickets->currentPage())
                                <span class="flex items-center justify-center text-indigo-600 font-bold bg-indigo-50 border border-indigo-300 w-9 h-9">{{ $i }}</span>
                            @else
                                <a href="{{ $historyTickets->appends(request()->query())->url($i) }}" class="flex items-center justify-center text-gray-600 bg-white border border-gray-300 hover:bg-indigo-50 hover:text-indigo-600 w-9 h-9 transition-all">{{ $i }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li>
                        @if ($historyTickets->hasMorePages())
                            <a href="{{ $historyTickets->appends(request()->query())->nextPageUrl() }}" class="flex items-center justify-center text-gray-600 bg-white border border-gray-300 hover:bg-indigo-50 hover:text-indigo-600 px-3 h-9 rounded-e-lg transition-all">Next</a>
                        @else
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 rounded-e-lg px-3 h-9 cursor-not-allowed select-none">Next</span>
                        @endif
                    </li>
                </ul>
            </nav>
            @endif
        </div>
    </div>

</div>
@endsection
