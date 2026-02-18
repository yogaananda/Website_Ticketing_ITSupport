@extends('layouts.layout')

@section('title', 'Monitor Status IT')

@section('content')

<div class="max-w-[85rem] px-4 py-8 sm:px-6 lg:px-8 mx-auto">
    <div class="mb-8 text-center">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">IT SERVICE MONITOR</h2>
        <p class="text-gray-500 mt-1">Pantau status pengerjaan tiket secara Realtime.</p>
    </div>
    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-8 rounded-r-lg shadow-sm">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-500 animate-pulse" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                </svg>
            </div>
            <div class="ml-3">
                <p class="text-sm text-red-700 font-bold">
                    INFO GANGGUAN: Server Absensi sedang Maintenance. Estimasi Up: 14:00 WIB.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <div class="bg-white border border-blue-100 rounded-2xl shadow-lg overflow-hidden relative">
            <div class="bg-blue-600 p-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center gap-2">
                    <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    SEDANG DIKERJAKAN
                </h3>
                <span class="text-xs bg-blue-500 px-2 py-1 rounded text-white border border-blue-400">Tim IT sedang di lokasi</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($workingOn as $ticket)
                    <div class="p-4 flex items-center justify-between hover:bg-blue-50 transition-colors">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-xs">
                                #{{ substr($ticket->ticket_code, -3) }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $ticket->user->division ?? 'Umum' }}</p>
                                <p class="text-xs text-gray-500">{{ Str::limit($ticket->title, 25) }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                             <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                Teknisi OTW
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                        <p>Tidak ada tiket yang sedang aktif dikerjakan.</p>
                    </div>
                @endforelse
            </div>
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-400 rounded-full opacity-10 blur-xl"></div>
        </div>
        <div class="bg-white border border-gray-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="bg-gray-50 p-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-700 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    ANTRIAN MENUNGGU
                </h3>
                <span class="text-xs text-gray-500">Menunggu giliran</span>
            </div>

            <div class="divide-y divide-gray-100">
                @forelse($pendingQueue as $ticket)
                    <div class="p-4 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <span class="text-gray-400 font-mono text-xs">#{{ substr($ticket->ticket_code, -3) }}</span>
                            <span class="text-sm font-medium text-gray-700">{{ $ticket->category->name }}</span>
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $ticket->created_at->diffForHumans() }}
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-gray-400">
                        <p>Antrian kosong. Semua aman!</p>
                    </div>
                @endforelse
            </div>
            
            @if($pendingQueue->count() >= 10)
                <div class="p-2 text-center text-xs text-gray-400 bg-gray-50">
                    ... dan {{ $pendingQueue->count() - 10 }} antrian lainnya
                </div>
            @endif
        </div>

    </div>
    <div class="mt-8 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-green-50 rounded-xl p-4 flex items-center justify-center border border-green-100">
            <div class="text-center">
                <span class="block text-3xl font-extrabold text-green-600">{{ $completedToday }}</span>
                <span class="text-xs font-bold text-green-800 uppercase tracking-wide">Masalah Teratasi Hari Ini</span>
            </div>
        </div>
        </div>

</div>

<script>
    setTimeout(function(){
       window.location.reload(1);
    }, 60000);
</script>

@endsection