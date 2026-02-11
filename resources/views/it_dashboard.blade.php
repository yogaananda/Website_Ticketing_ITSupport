@extends('layouts.layout')

@section('title', 'IT Dashboard')

@section('content')
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
        <div>
        <h1 class="text-2xl font-bold text-gray-800 dark:text-dark-neutral-200">
            {{-- NAMA USER DINAMIS --}}
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
                            <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded text-xs font-medium bg-green-50 text-green-700 border border-green-200">Selesai</span>
                        @endif
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