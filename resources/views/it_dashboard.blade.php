@extends('layouts.layout')

@section('title', 'IT Dashboard')

@section('content')
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-dark-neutral-200">
            Halo, {{ Auth::user()->full_name ?? Auth::user()->username }}
        </h1>
        <p class="mt-1 text-gray-600 dark:text-neutral-400">
            Selamat datang di IT Support Dashboard.
        </p>
        </div>
  </div>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="flex items-center p-4 bg-red-50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="p-3 mr-4 text-red-500 bg-red-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Urgent / Darurat</p>
                <p class="text-2xl font-bold text-gray-900">{{ $urgent }}</p>
            </div>
        </div>

        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-3 mr-4 text-blue-500 bg-blue-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Tiket Baru</p>
                <p class="text-2xl font-bold text-gray-900">{{ $newTickets }}</p>
            </div>
        </div>
        
        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-3 mr-4 text-yellow-500 bg-yellow-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Sedang Dikerjakan</p>
                <p class="text-2xl font-bold text-gray-900">{{ $onProgress }}</p>
            </div>
        </div>

        <div class="flex items-center p-4 bg-white border border-gray-200 rounded-lg shadow-sm">
            <div class="p-3 mr-4 text-green-500 bg-green-100 rounded-full">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <p class="mb-1 text-sm font-medium text-gray-500">Selesai Hari Ini</p>
                <p class="text-2xl font-bold text-gray-900">{{ $completedToday }}</p>
            </div>
        </div>
    </div>

    <div class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
        <div class="p-4 flex flex-col sm:flex-row items-center justify-between gap-4">
            <form action="{{ route('it.dashboard') }}" method="GET" class="relative w-full sm:max-w-xs">
                @if(request('status'))
                    <input type="hidden" name="status" value="{{ request('status') }}">
                @endif

                <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/></svg>
                </div>
                <input type="text" name="search" id="table-search" value="{{ request('search') }}" 
                    class="block w-full ps-10 p-2.5 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 placeholder-gray-400" 
                    placeholder="Cari ID, Barang, Pelapor...">
            </form>
            <div class="relative">
                <button id="filterDropdownButton" data-dropdown-toggle="filterDropdown" class="inline-flex items-center text-gray-500 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-100 font-medium rounded-lg text-sm px-3 py-2" type="button">
                    <svg class="w-3 h-3 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7.75 4H19M7.75 4a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 4h2.25m13.5 6H19m-2.25 0a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 10h11.25m-4.5 6H19M7.75 16a2.25 2.25 0 0 1-4.5 0m4.5 0a2.25 2.25 0 0 0-4.5 0M1 16h2.25"/>
                    </svg>
                    @if(request('status') == 'high')
                        Urgent / High
                    @elseif(request('status') == 'open')
                        Belum Dikerjakan
                    @elseif(request('status') == 'on_progress')
                        Sedang Proses
                    @else
                        Filter Status
                    @endif

                    <svg class="w-2.5 h-2.5 ms-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <div id="filterDropdown" class="z-10 hidden w-48 bg-white divide-y divide-gray-100 rounded-lg shadow-lg border border-gray-100">
                    <ul class="p-3 space-y-1 text-sm text-gray-700" aria-labelledby="filterDropdownButton">
                        <li>
                            <a href="{{ route('it.dashboard', array_merge(request()->query(), ['status' => 'high'])) }}" class="flex items-center p-2 rounded hover:bg-gray-100 group">
                                <span class="w-2 h-2 me-2 rounded-full bg-red-500"></span>
                                Urgent / High
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('it.dashboard', array_merge(request()->query(), ['status' => 'open'])) }}" class="flex items-center p-2 rounded hover:bg-gray-100 group">
                                <span class="w-2 h-2 me-2 rounded-full bg-blue-500"></span>
                                Belum Dikerjakan
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('it.dashboard', array_merge(request()->query(), ['status' => 'on_progress'])) }}" class="flex items-center p-2 rounded hover:bg-gray-100 group">
                                <span class="w-2 h-2 me-2 rounded-full bg-yellow-400"></span>
                                Sedang Proses
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('it.dashboard', request()->except('status')) }}" class="flex items-center p-2 rounded hover:bg-gray-100 text-gray-500">
                                <svg class="w-4 h-4 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                Reset Filter
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <table class="w-full text-sm text-left text-body">
            <thead class="text-sm text-body bg-neutral-secondary-medium border-b border-t border-default-medium">
                <tr>
                    <th scope="col" class="p-4"><input type="checkbox" class="w-4 h-4 border-default-medium rounded-xs"></th>
                    <th scope="col" class="px-6 py-3 font-medium">Tiket & Waktu</th>
                    <th scope="col" class="px-6 py-3 font-medium">Barang & Masalah</th>
                    <th scope="col" class="px-6 py-3 font-medium">Pelapor</th>
                    <th scope="col" class="px-6 py-3 font-medium">Prioritas</th>
                    <th scope="col" class="px-6 py-3 font-medium text-center">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tickets as $ticket)
                <tr class="bg-neutral-primary-soft border-b border-default hover:bg-neutral-secondary-medium transition-colors">
                    <td class="w-4 p-4"><input type="checkbox" class="w-4 h-4 border-default-medium rounded-xs"></td>
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        #{{ $ticket->ticket_code }} <br>
                        @if($ticket->created_at->diffInHours() < 1)
                            <span class="text-xs text-red-600 font-normal">{{ $ticket->created_at->diffForHumans() }}</span>
                        @else
                            <span class="text-xs text-body font-normal">{{ $ticket->created_at->diffForHumans() }}</span>
                        @endif
                    </th>
                    <td class="px-6 py-4">
                        <div class="text-heading font-medium">{{ Str::limit($ticket->title, 20) }}</div>
                        <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 30) }}</div>
                    </td>
                    <td class="px-6 py-4">{{ $ticket->user->full_name ?? $ticket->user->username }}</td>
                    <td class="px-6 py-4">
                        @if($ticket->priority == 'high')
                            <span class="bg-red-100 text-red-800 text-xs font-medium px-2.5 py-0.5 rounded border border-red-400">High / Urgent</span>
                        @elseif($ticket->priority == 'medium')
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded border border-yellow-300">Medium</span>
                        @else
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-300">Low</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        @if($ticket->status == 'open')
                            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <input type="hidden" name="action_type" value="progress">
                                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-xs px-4 py-2 transition-all">Ambil Tiket</button>
                            </form>
                        @elseif($ticket->status == 'in_progress')
                            @if($ticket->assigned_to == Auth::id())
                                <button data-modal-target="modal-action-{{ $ticket->id }}" data-modal-toggle="modal-action-{{ $ticket->id }}" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-xs px-4 py-2 flex items-center justify-center gap-1 mx-auto transition-all">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                    Tindak Lanjut
                                </button>
                                <div id="modal-action-{{ $ticket->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left">
                                    <div class="relative p-4 w-full max-w-lg max-h-full">
                                        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-100">
                                            <form action="{{ route('tickets.update', $ticket->id) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <div class="flex items-center justify-between p-4 border-b border-gray-100">
                                                    <h3 class="text-lg font-bold text-gray-800">Update Tiket <span class="text-indigo-600">#{{ $ticket->ticket_code }}</span></h3>
                                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 rounded-lg text-sm w-8 h-8" data-modal-hide="modal-action-{{ $ticket->id }}">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                                    </button>
                                                </div>
                                                <div class="p-5">
                                                    <div class="grid w-full gap-3 md:grid-cols-3 mb-5">
                                                        <div>
                                                            <input type="radio" id="act-p-{{ $ticket->id }}" name="action_type" value="progress" class="hidden peer" checked>
                                                            <label for="act-p-{{ $ticket->id }}" class="inline-flex flex-col items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-blue-600 peer-checked:text-blue-600 hover:text-gray-600 hover:bg-gray-50 transition-all shadow-sm h-full ring-0 peer-checked:ring-1 peer-checked:ring-blue-600">
                                                                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                                                <div class="w-full text-xs font-semibold text-center">Update Progres</div>
                                                                <div class="w-full text-[10px] text-center mt-1 text-gray-400 leading-tight">Catat aktivitas harian</div>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" id="act-r-{{ $ticket->id }}" name="action_type" value="resolved" class="hidden peer">
                                                            <label for="act-r-{{ $ticket->id }}" class="inline-flex flex-col items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-green-600 peer-checked:text-green-600 hover:text-gray-600 hover:bg-gray-50 transition-all shadow-sm h-full ring-0 peer-checked:ring-1 peer-checked:ring-green-600">
                                                                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                <div class="w-full text-xs font-semibold text-center">Selesaikan</div>
                                                                <div class="w-full text-[10px] text-center mt-1 text-gray-400 leading-tight">Masalah tuntas diperbaiki</div>
                                                            </label>
                                                        </div>
                                                        <div>
                                                            <input type="radio" id="act-c-{{ $ticket->id }}" name="action_type" value="cancel" class="hidden peer">
                                                            <label for="act-c-{{ $ticket->id }}" class="inline-flex flex-col items-center justify-between w-full p-3 text-gray-500 bg-white border border-gray-200 rounded-lg cursor-pointer peer-checked:border-red-600 peer-checked:text-red-600 hover:text-gray-600 hover:bg-gray-50 transition-all shadow-sm h-full ring-0 peer-checked:ring-1 peer-checked:ring-red-600">
                                                                <svg class="w-6 h-6 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                                <div class="w-full text-xs font-semibold text-center">Lepas Tiket</div>
                                                                <div class="w-full text-[10px] text-center mt-1 text-gray-400 leading-tight">Kembalikan ke Open</div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <label class="block mb-2 text-sm font-medium text-gray-900">Catatan Aktivitas:</label>
                                                    <textarea name="note" rows="3" class="block p-3 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500" placeholder="Apa yang Anda kerjakan?"></textarea>
                                                </div>
                                                <div class="flex items-center p-4 border-t border-gray-100 bg-gray-50 rounded-b-xl">
                                                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">Simpan Update</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="flex flex-col items-center">
                                    <span class="bg-gray-100 text-gray-600 text-[10px] px-2 py-1 rounded border border-gray-200">Ditangani oleh:</span>
                                    @if ($ticket->technician)
                                        {{$ticket->technician->full_name}}
                                    @else
                                        <span class="text-xs font-bold text-gray-800">lainya</span>
                                    @endif
                                </div>
                            @endif
                        @else
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200 shadow-sm">Selesai</span>
                        @endif
                        
                        <div class="mt-3">
                            <button type="button" data-modal-target="modal-it-detail-{{ $ticket->id }}" data-modal-toggle="modal-it-detail-{{ $ticket->id }}" class="text-indigo-600 bg-indigo-50 border border-indigo-200 hover:bg-indigo-100 hover:text-indigo-800 font-medium rounded-lg text-xs px-4 py-1.5 transition-all w-full md:w-auto shadow-sm">
                                Lihat Detail
                            </button>
                        </div>
                        
                        <!-- Modal Detail (IT) -->
                        <div id="modal-it-detail-{{ $ticket->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left whitespace-normal">
                            <div class="relative p-4 w-full max-w-4xl max-h-full">
                                <div class="relative bg-white border border-gray-200 rounded-xl shadow-2xl">
                                    <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-100 rounded-t bg-gray-50/50">
                                        <div>
                                            <h3 class="text-lg font-bold text-gray-900">
                                                Detail Tiket #{{ $ticket->ticket_code }}
                                            </h3>
                                            <p class="text-xs text-gray-500 mt-1">Laporan rekan kerja & log aktivitas teknisi</p>
                                        </div>
                                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors" data-modal-hide="modal-it-detail-{{ $ticket->id }}">
                                            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        </button>
                                    </div>
                                    <div class="p-4 md:p-6 space-y-6">
                                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-dashed border-gray-200 pb-6">
                                            <div>
                                                <h3 class="text-xl font-bold text-gray-900">{{ $ticket->title }}</h3>
                                                <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                    <span>Pelapor: <span class="font-medium text-gray-700">{{ $ticket->user->full_name ?? $ticket->user->username ?? 'User' }}</span></span>
                                                    <span class="text-gray-300">|</span>
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    <span>{{ $ticket->created_at->format('d M Y, H:i') }} WIB</span>
                                                </div>
                                            </div>
                                            <div class="flex items-center gap-2 shrink-0">
                                                @if($ticket->priority == 'high')
                                                    <span class="bg-red-100 text-red-800 text-xs font-bold px-3 py-1.5 rounded-full border border-red-200 flex items-center gap-1 shadow-sm"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg> Urgent</span>
                                                @elseif($ticket->priority == 'medium')
                                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1.5 rounded-full border border-yellow-200 flex items-center gap-1 shadow-sm"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg> Medium</span>
                                                @else
                                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1.5 rounded-full border border-green-200 flex items-center gap-1 shadow-sm"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Low</span>
                                                @endif

                                                @if($ticket->status == 'open')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                                        <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg> Menunggu
                                                    </span>
                                                @elseif($ticket->status == 'in_progress')
                                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                                        <svg class="size-2.5 fill-current animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg> Diproses
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                                        <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg> Selesai
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                            <div>
                                                <h4 class="font-bold text-gray-900 mb-3 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Deskripsi Masalah
                                                </h4>
                                                <div class="bg-gray-50 p-4 rounded-xl border border-gray-100 text-sm text-gray-700 leading-relaxed">
                                                    {{ $ticket->description }}
                                                </div>
                                                
                                                <h4 class="font-bold text-gray-900 mt-8 mb-3 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    Bukti Foto
                                                </h4>
                                                @if($ticket->image_path)
                                                    <a href="{{ asset('storage/' . $ticket->image_path) }}" target="_blank" class="block w-full relative overflow-hidden rounded-xl border border-gray-200 group shadow-sm">
                                                        <img src="{{ asset('storage/' . $ticket->image_path) }}" class="w-full h-auto object-cover transition-transform duration-300 hover:scale-[1.02]">
                                                    </a>
                                                @else
                                                    <div class="w-full h-32 bg-gray-50 rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 text-sm">
                                                        <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Tidak ada lampiran foto
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="md:border-s md:border-gray-100 md:ps-8">
                                                <h4 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                                                    <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                    Riwayat Aktivitas
                                                </h4>
                                                <div class="h-[300px] overflow-y-auto pe-2 scrollbar-thin scrollbar-thumb-gray-200">
                                                    <ol class="relative border-s border-gray-200 ml-2 mt-1">
                                                        <li class="mb-8 ms-6">
                                                            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-4 ring-white">
                                                                <svg class="w-2.5 h-2.5 text-blue-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                                                            </span>
                                                            <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Tiket Dibuat</h3>
                                                            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $ticket->created_at->format('d M, H:i') }}</time>
                                                            <p class="text-xs text-gray-500">Laporan masuk ke sistem oleh {{ $ticket->user->full_name ?? 'Pelapor' }}.</p>
                                                        </li>
                                                        @forelse($ticket->comments ?? [] as $comment)
                                                            <li class="mb-8 ms-6 text-left">
                                                                @php
                                                                    $msgUpper = strtoupper($comment->message);
                                                                    $isDone = str_contains($msgUpper, 'SELESAI');
                                                                    $isReturned = str_contains($msgUpper, 'DIKEMBALIKAN');
                                                                    $dotBg = 'bg-gray-100';
                                                                    $dotIconColor = 'text-gray-600';
                                                                    if($isDone) { $dotBg = 'bg-green-100'; $dotIconColor = 'text-green-800'; }
                                                                    elseif($isReturned) { $dotBg = 'bg-orange-100'; $dotIconColor = 'text-orange-800'; }
                                                                @endphp
                                                                <span class="absolute flex items-center justify-center w-6 h-6 {{ $dotBg }} rounded-full -start-3 ring-4 ring-white shadow-sm">
                                                                    @if($isDone)
                                                                        <svg class="w-3 h-3 {{ $dotIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                    @elseif($isReturned)
                                                                        <svg class="w-3 h-3 {{ $dotIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                                                                    @else
                                                                        <svg class="w-3 h-3 {{ $dotIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                                    @endif
                                                                </span>
                                                                <h3 class="mb-1 text-sm font-bold text-gray-900">{{ $comment->user->full_name ?? 'Teknisi / Sistem' }}</h3>
                                                                <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $comment->created_at->format('d M, H:i') }}</time>
                                                                <div class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg border border-gray-200 shadow-sm leading-relaxed relative mt-1">
                                                                    <div class="absolute w-2 h-2 bg-gray-50 border-t border-l border-gray-200 transform -translate-y-1/2 rotate-45 -left-1 top-4"></div>
                                                                    {{ $comment->message }}
                                                                </div>
                                                            </li>
                                                        @empty
                                                        @endforelse
                                                    </ol>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-100 rounded-b bg-gray-50/50">
                                        <button data-modal-hide="modal-it-detail-{{ $ticket->id }}" type="button" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 transition-colors">
                                            Selesai / Tutup Detail
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Tidak ada tiket yang ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>

        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-normal text-gray-500">
                Showing <span class="font-semibold text-gray-900">{{ $tickets->firstItem() ?? 0 }}</span> 
                to <span class="font-semibold text-gray-900">{{ $tickets->lastItem() ?? 0 }}</span> 
                of <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span> results
            </div>

            @if ($tickets->hasPages())
            <nav aria-label="Pagination">
                <ul class="flex -space-x-px text-sm">
                    <li>
                        @if ($tickets->onFirstPage())
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 rounded-s-lg px-3 h-10 cursor-not-allowed">Previous</span>
                        @else
                            <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 px-3 h-10 transition-all rounded-s-lg">Previous</a>
                        @endif
                    </li>
                    @foreach (range(1, $tickets->lastPage()) as $i)
                        <li>
                            @if ($i == $tickets->currentPage())
                                <span class="flex items-center justify-center text-blue-600 bg-blue-50 border border-gray-300 font-bold w-10 h-10">{{ $i }}</span>
                            @else
                                <a href="{{ $tickets->appends(request()->query())->url($i) }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 w-10 h-10 transition-all">{{ $i }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li>
                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->appends(request()->query())->nextPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white border border-gray-300 hover:bg-gray-100 px-3 h-10 transition-all rounded-e-lg">Next</a>
                        @else
                            <span class="flex items-center justify-center text-gray-400 bg-white border border-gray-300 rounded-e-lg px-3 h-10 cursor-not-allowed">Next</span>
                        @endif
                    </li>
                </ul>
            </nav>
            @endif
        </div>
    </div>
</div>
@endsection