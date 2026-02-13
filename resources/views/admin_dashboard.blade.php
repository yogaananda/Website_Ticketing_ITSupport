@extends('layouts.layout')

@section('title', 'Admin Dashboard')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto p-4 space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-dark-neutral-200">
            Halo, {{ Auth::user()->full_name ?? Auth::user()->username }}
        </h1>
        <p class="mt-1 text-gray-600 dark:text-neutral-400">
            Selamat datang di IT Support Center. Ada yang bisa kami bantu?
        </p>
        </div>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex items-center">
                <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Tiket Bulan Ini</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalTicketsMonth }}</p>
                </div>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex items-center">
                <div class="p-3 mr-4 text-yellow-500 bg-yellow-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sedang Diperbaiki</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $workingOnIt }}</p>
                </div>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex items-center">
                <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Selesai Diperbaiki</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $resolved }}</p>
                </div>
            </div>
            <div class="p-4 bg-white border border-gray-200 rounded-lg shadow-sm flex items-center">
                <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Belum Ditangani</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $unhandled }}</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8"> 
            
            <div class="lg:col-span-2 w-full bg-white border border-gray-200 rounded-lg shadow-sm p-4 md:p-6">
                
                <div class="flex justify-between pb-4 mb-4 border-b border-gray-200">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center me-3">
                            <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div>
                            <h5 class="text-2xl font-bold text-gray-900">{{ $totalWeekly }}</h5>
                            <p class="text-sm font-normal text-gray-500">Tiket masuk 7 hari terakhir</p>
                        </div>
                    </div>
                    
                    <div>
                        <span class="bg-green-100 text-green-800 text-xs font-medium inline-flex items-center px-2.5 py-1 rounded-md">
                            <svg class="w-2.5 h-2.5 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13V1m0 0L1 5m4-4 4 4"/></svg>
                            Data Live
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 mb-4">
                    <dl class="flex items-center">
                        <dt class="text-gray-500 text-sm font-normal me-1">Selesai:</dt>
                        <dd class="text-gray-900 text-sm font-semibold">{{ $resolvedWeekly }}</dd>
                    </dl>
                    <dl class="flex items-center justify-end">
                        <dt class="text-gray-500 text-sm font-normal me-1">Pending:</dt>
                        <dd class="text-gray-900 text-sm font-semibold">{{ $pendingWeekly }}</dd>
                    </dl>
                </div>

                <div id="column-chart"></div>
                
                <div class="grid grid-cols-1 items-center border-t border-gray-200 justify-between mt-5">
                    <div class="flex justify-between items-center pt-5">
                        <button class="text-sm font-medium text-gray-500 hover:text-gray-900 text-center inline-flex items-center" type="button">
                            Last 7 days
                            <svg class="w-2.5 h-2.5 ms-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/></svg>
                        </button>
                        <a href="{{ route('admin.report') }}" class="uppercase text-sm font-semibold inline-flex items-center rounded-lg text-blue-600 hover:text-blue-700 hover:bg-gray-100 px-3 py-2">
                            FULL REPORT
                            <svg class="w-2.5 h-2.5 ms-1.5 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/></svg>
                        </a>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <span class="w-3 h-3 bg-yellow-400 rounded-full mr-2"></span>
                    On Progress
                </h3>
                <ul class="divide-y divide-gray-200">
                    @forelse($ongoingTickets as $ticket)
                    <li class="py-3">
                        <div class="flex items-center space-x-3">
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $ticket->title }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $ticket->ticket_code }}</p>
                            </div>
                            @if($ticket->priority == 'high')
                                <span class="inline-flex items-center bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded">High</span>
                            @elseif($ticket->priority == 'medium')
                                <span class="inline-flex items-center bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded">Med</span>
                            @else
                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">Low</span>
                            @endif
                        </div>
                    </li>
                    @empty
                    <li class="py-3 text-center text-sm text-gray-500">Tidak ada tiket aktif.</li>
                    @endforelse
                </ul>
            </div>
        </div>     
        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 mt-8">
            <h3 class="text-lg font-bold text-gray-900 mb-4 text-dark">Riwayat Perbaikan Selesai</h3>
            
            <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">

                <table class="w-full text-sm text-left rtl:text-right text-body">
                    <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium">
                        <tr>
                            <th scope="col" class="px-6 py-3 font-medium">Tiket & Barang</th>
                            <th scope="col" class="px-6 py-3 font-medium">Masalah</th>
                            <th scope="col" class="px-6 py-3 font-medium">Pelapor</th>
                            <th scope="col" class="px-6 py-3 font-medium">Tgl Selesai</th>
                            <th scope="col" class="px-6 py-3 font-medium">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($historyTickets as $ticket)
                        <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                            <td class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                                <span class="text-xs text-gray-500">{{ $ticket->ticket_code }}</span> <br>
                                {{ Str::limit($ticket->title, 20) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ Str::limit($ticket->description, 30) }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $ticket->user->full_name ?? $ticket->user->username }}
                            </td>

                            <td class="px-6 py-4">
                                {{ $ticket->updated_at->format('d M Y') }} <br>
                                <span class="text-xs text-gray-400">{{ $ticket->updated_at->format('H:i') }}</span>
                            </td>

                            <td class="px-6 py-4">
                                <span class="inline-flex items-center bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-300">
                                    Selesai
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                                Belum ada riwayat perbaikan yang selesai.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-normal text-gray-500">
                Showing <span class="font-semibold text-gray-900">{{ $historyTickets->firstItem() ?? 0 }}</span> 
                to <span class="font-semibold text-gray-900">{{ $historyTickets->lastItem() ?? 0 }}</span> 
                of <span class="font-semibold text-gray-900">{{ $historyTickets->total() }}</span> results
            </div>

            @if ($historyTickets->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="flex -space-x-px text-sm">
                    <li>
                        @if ($historyTickets->onFirstPage())
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 font-medium rounded-s-lg px-3 h-10 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $historyTickets->appends(request()->query())->previousPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-s-lg px-3 h-10 transition-colors">Previous</a>
                        @endif
                    </li>

                    @foreach (range(1, $historyTickets->lastPage()) as $i)
                        <li>
                            @if ($i == $historyTickets->currentPage())
                                <span class="flex items-center justify-center text-blue-600 bg-blue-50 border border-gray-300 font-bold w-10 h-10">{{ $i }}</span>
                            @else
                                <a href="{{ $historyTickets->appends(request()->query())->url($i) }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 font-medium w-10 h-10 transition-colors">{{ $i }}</a>
                            @endif
                        </li>
                    @endforeach

                    <li>
                        @if ($historyTickets->hasMorePages())
                            <a href="{{ $historyTickets->appends(request()->query())->nextPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 font-medium rounded-e-lg px-3 h-10 transition-colors">Next</a>
                        @else
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 font-medium rounded-e-lg px-3 h-10 cursor-not-allowed">Next</span>
                        @endif
                    </li>
                </ul>
            </nav>
            @endif
        </div>
    </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 mt-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Statistik Perbaikan (Tahun {{ date('Y') }})</h3>
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-400">
                    Tahun Ini
                </span>
            </div>
            
            <div id="yearlyChart" class="w-full h-72"></div>
        </div>
    </div>
</div>

<script>
    window.chartData = {
        labels: @json($weeklyLabels ?? []),
        completed: @json($completedData ?? []),
        pending: @json($pendingData ?? []),
        yearlyLabels: @json($yearlyLabels ?? []),
        yearlyCompleted: @json($yearlyCompleted ?? []),
        yearlyPending: @json($yearlyPending ?? [])
    };
</script>
@endsection