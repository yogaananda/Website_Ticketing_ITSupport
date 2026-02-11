@extends('layouts.layout')

@section('title', 'Laporan Lengkap')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">
    
    {{-- HEADER HALAMAN --}}
    <div class="flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Laporan Lengkap</h2>
            <p class="text-sm text-gray-600">Arsip semua tiket (Open, Proses, Selesai)</p>
        </div>
    </div>

    {{-- FILTER FORM --}}
    <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-4 mt-8">
        <form action="{{ route('admin.report') }}" method="GET" class="mb-0">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                
                {{-- Input Pencarian --}}
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium mb-2 text-gray-700">Cari</label>
                    <input type="text" name="search" value="{{ request('search') }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm focus:border-indigo-500 focus:ring-indigo-500 bg-gray-50 border" placeholder="Kode / Judul...">
                </div>

                {{-- Input Tanggal Mulai --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Dari Tanggal</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm bg-gray-50 border">
                </div>

                {{-- Input Tanggal Akhir --}}
                <div>
                    <label class="block text-sm font-medium mb-2 text-gray-700">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="py-3 px-4 block w-full border-gray-200 rounded-lg text-sm bg-gray-50 border">
                </div>

                {{-- Tombol Filter --}}
                <div>
                    <button type="submit" class="w-full py-3 px-4 inline-flex justify-center items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 transition-colors">
                        Filter Data
                    </button>
                </div>
            </div>
        </form>
    </div>
        
    {{-- TABEL DATA --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                
                {{-- THEAD --}}
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Tanggal</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Tiket & Barang</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Pelapor</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Status</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Aksi</th>
                    </tr>
                </thead>
                
                {{-- TBODY --}}
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($tickets as $ticket)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        
                        {{-- 1. TANGGAL --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $ticket->created_at->timezone('Asia/Jakarta')->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $ticket->created_at->timezone('Asia/Jakarta')->format('H:i') }} WIB</div>
                        </td>

                        {{-- 2. TIKET & BARANG --}}
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">#{{ $ticket->ticket_code }}</div>
                            <div class="text-sm text-gray-500">{{ Str::limit($ticket->title, 30) }}</div>
                        </td>

                        {{-- 3. PELAPOR --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $ticket->user->full_name ?? 'User' }}</div>
                            <div class="text-xs text-gray-500">{{ $ticket->category->name ?? 'Umum' }}</div>
                        </td>

                        {{-- 4. STATUS --}}
                        <td class="px-6 py-4">
                            @if($ticket->status == 'open')
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                    <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                                    Menunggu
                                </span>
                            @elseif($ticket->status == 'in_progress')
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                    <svg class="size-2.5 fill-current animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg>
                                    Diproses
                                </span>
                            @else
                                <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                    <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>
                                    Selesai
                                </span>
                            @endif
                        </td>

                        {{-- 5. AKSI & MODAL --}}
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <button type="button" 
                                    data-modal-target="modal-detail-{{ $ticket->id }}" 
                                    data-modal-toggle="modal-detail-{{ $ticket->id }}" 
                                    class="text-indigo-600 hover:text-indigo-900 hover:underline transition-colors">
                                Detail
                            </button>

                            {{-- MODAL --}}
                            {{-- MODAL DETAIL (FULL REPORT STYLE) --}}
                            <div id="modal-detail-{{ $ticket->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left whitespace-normal">
                                <div class="relative p-4 w-full max-w-4xl max-h-full">
                                    <div class="relative bg-white border border-gray-200 rounded-xl shadow-2xl">
                                        
                                        {{-- 1. HEADER MODAL --}}
                                        <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-100 rounded-t bg-gray-50/50">
                                            <div>
                                                <h3 class="text-lg font-bold text-gray-900">
                                                    Detail Tiket #{{ $ticket->ticket_code }}
                                                </h3>
                                                <p class="text-xs text-gray-500 mt-1">Laporan lengkap & riwayat aktivitas</p>
                                            </div>
                                            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors" data-modal-hide="modal-detail-{{ $ticket->id }}">
                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                                <span class="sr-only">Close modal</span>
                                            </button>
                                        </div>
                                        
                                        {{-- 2. BODY MODAL --}}
                                        <div class="p-4 md:p-6 space-y-6">
                                            
                                            {{-- A. Info Utama (Judul & Status) --}}
                                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-dashed border-gray-200 pb-6">
                                                <div>
                                                    <h3 class="text-xl font-bold text-gray-900">{{ $ticket->title }}</h3>
                                                    <div class="flex items-center gap-2 mt-1 text-sm text-gray-500">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                                        <span>Pelapor: <span class="font-medium text-gray-700">{{ $ticket->user->full_name ?? 'User' }}</span></span>
                                                        <span class="text-gray-300">|</span>
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        <span>{{ $ticket->created_at->translatedFormat('d F Y, H:i') }} WIB</span>
                                                    </div>
                                                </div>

                                                {{-- Status Badge --}}
                                                <div class="shrink-0">
                                                    @if($ticket->status == 'open')
                                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200 shadow-sm">
                                                            <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                                                            Menunggu
                                                        </span>
                                                    @elseif($ticket->status == 'in_progress')
                                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200 shadow-sm">
                                                            <svg class="size-2.5 fill-current animate-spin" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M11.534 7h3.932a.25.25 0 0 1 .192.41l-1.966 2.36a.25.25 0 0 1-.384 0l-1.966-2.36a.25.25 0 0 1 .192-.41zm-11 2h3.932a.25.25 0 0 0 .192-.41L2.692 6.23a.25.25 0 0 0-.384 0L.342 8.59A.25.25 0 0 0 .534 9z"/><path fill-rule="evenodd" d="M8 3c-1.552 0-2.94.707-3.857 1.818a.5.5 0 1 1-.771-.636A6.002 6.002 0 0 1 13.917 7H12.9A5.002 5.002 0 0 0 8 3zM3.1 9a5.002 5.002 0 0 0 8.757 2.182.5.5 0 1 1 .771.636A6.002 6.002 0 0 1 2.083 9H3.1z"/></svg>
                                                            Diproses
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm">
                                                            <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>
                                                            Selesai
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- B. Grid Layout (Kiri: Fisik, Kanan: Log) --}}
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                                
                                                {{-- KOLOM KIRI: Deskripsi & Foto --}}
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
                                                    @if($ticket->image)
                                                        <div class="relative group">
                                                            <img src="{{ asset('storage/' . $ticket->image) }}" class="rounded-xl border border-gray-200 w-full h-auto object-cover shadow-sm transition-transform hover:scale-[1.02]">
                                                        </div>
                                                    @else
                                                        <div class="w-full h-32 bg-gray-50 rounded-xl border border-dashed border-gray-300 flex flex-col items-center justify-center text-gray-400 text-sm">
                                                            <svg class="w-8 h-8 mb-1 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                            Tidak ada lampiran foto
                                                        </div>
                                                    @endif
                                                </div>

                                                {{-- KOLOM KANAN: Timeline / Log Aktivitas --}}
                                                <div class="md:border-s md:border-gray-100 md:ps-8">
                                                    <h4 class="font-bold text-gray-900 mb-6 flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                        Riwayat Aktivitas
                                                    </h4>
                                                    
                                                    <ol class="relative border-s border-gray-200 ml-2">                  
                                                        {{-- LOG 1: DIBUAT --}}
                                                        <li class="mb-8 ms-6">
                                                            <span class="absolute flex items-center justify-center w-6 h-6 bg-blue-100 rounded-full -start-3 ring-4 ring-white">
                                                                <svg class="w-2.5 h-2.5 text-blue-800" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/></svg>
                                                            </span>
                                                            <h3 class="flex items-center mb-1 text-sm font-semibold text-gray-900">Tiket Dibuat</h3>
                                                            <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $ticket->created_at->translatedFormat('d M Y, H:i') }}</time>
                                                            <p class="text-xs text-gray-500">Laporan masuk ke sistem oleh {{ $ticket->user->full_name ?? 'User' }}.</p>
                                                        </li>

                                                        {{-- LOG 2: KOMENTAR & UPDATE --}}
                                                        @forelse($ticket->comments as $comment)
                                                            <li class="mb-8 ms-6">
                                                                @php 
                                                                    // Logika warna titik: Hijau jika pesan mengandung "Selesai", Biru untuk lainnya
                                                                    $isDone = str_contains(strtoupper($comment->message), 'SELESAI');
                                                                    $dotBg = $isDone ? 'bg-green-100' : 'bg-gray-100';
                                                                    $dotIconColor = $isDone ? 'text-green-800' : 'text-gray-600';
                                                                @endphp
                                                                <span class="absolute flex items-center justify-center w-6 h-6 {{ $dotBg }} rounded-full -start-3 ring-4 ring-white">
                                                                    @if($isDone)
                                                                        <svg class="w-3 h-3 {{ $dotIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                                                    @else
                                                                        <svg class="w-3 h-3 {{ $dotIconColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                                                                    @endif
                                                                </span>
                                                                <h3 class="mb-1 text-sm font-semibold text-gray-900">{{ $comment->user->full_name ?? 'Teknisi' }}</h3>
                                                                <time class="block mb-2 text-xs font-normal leading-none text-gray-400">{{ $comment->created_at->translatedFormat('d M, H:i') }}</time>
                                                                <div class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100">
                                                                    {{ $comment->message }}
                                                                </div>
                                                            </li>
                                                        @empty
                                                            <li class="mb-8 ms-6">
                                                                <span class="absolute flex items-center justify-center w-6 h-6 bg-gray-100 rounded-full -start-3 ring-4 ring-white">
                                                                    <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                                                                </span>
                                                                <p class="text-xs text-gray-400 italic">Belum ada aktivitas tindak lanjut.</p>
                                                            </li>
                                                        @endforelse
                                                    </ol>
                                                </div>

                                            </div>
                                        </div>

                                        {{-- 3. FOOTER MODAL --}}
                                        <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-100 rounded-b bg-gray-50/50">
                                            <button data-modal-hide="modal-detail-{{ $ticket->id }}" type="button" class="text-gray-700 bg-white border border-gray-300 focus:ring-4 focus:outline-none focus:ring-gray-100 font-medium rounded-lg text-sm px-5 py-2.5 hover:bg-gray-50 hover:text-gray-900 transition-colors">
                                                Tutup
                                            </button>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500">
                            Belum ada data laporan yang sesuai filter.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- PAGINATION CUSTOM (SESUAI REQUEST) --}}
        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            
            {{-- 1. INFO SHOWING DATA --}}
            <div class="text-sm font-normal text-gray-500">
                Showing 
                <span class="font-semibold text-gray-900">{{ $tickets->firstItem() ?? 0 }}</span> 
                to 
                <span class="font-semibold text-gray-900">{{ $tickets->lastItem() ?? 0 }}</span> 
                of 
                <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span> 
                results
            </div>

            {{-- 2. NAVIGASI TOMBOL --}}
            @if ($tickets->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="flex -space-x-px text-sm">
                    
                    {{-- Previous --}}
                    <li>
                        @if ($tickets->onFirstPage())
                            <span class="flex items-center justify-center text-gray-400 bg-white box-border border border-gray-300 font-medium rounded-s-lg text-sm px-3 h-10 cursor-not-allowed">
                                Previous
                            </span>
                        @else
                            <a href="{{ $tickets->appends(request()->query())->previousPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-gray-700 font-medium rounded-s-lg text-sm px-3 h-10 focus:outline-none transition-colors">
                                Previous
                            </a>
                        @endif
                    </li>

                    {{-- Angka Halaman --}}
                    @foreach (range(1, $tickets->lastPage()) as $i)
                        <li>
                            @if ($i == $tickets->currentPage())
                                <span aria-current="page" class="flex items-center justify-center text-blue-600 bg-blue-50 box-border border border-gray-300 hover:text-blue-700 font-bold text-sm w-10 h-10 focus:outline-none">
                                    {{ $i }}
                                </span>
                            @else
                                <a href="{{ $tickets->appends(request()->query())->url($i) }}" class="flex items-center justify-center text-gray-500 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-gray-700 font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                                    {{ $i }}
                                </a>
                            @endif
                        </li>
                    @endforeach

                    {{-- Next --}}
                    <li>
                        @if ($tickets->hasMorePages())
                            <a href="{{ $tickets->appends(request()->query())->nextPageUrl() }}" class="flex items-center justify-center text-gray-500 bg-white box-border border border-gray-300 hover:bg-gray-100 hover:text-gray-700 font-medium rounded-e-lg text-sm px-3 h-10 focus:outline-none transition-colors">
                                Next
                            </a>
                        @else
                            <span class="flex items-center justify-center text-gray-400 bg-white box-border border border-gray-300 font-medium rounded-e-lg text-sm px-3 h-10 cursor-not-allowed">
                                Next
                            </span>
                        @endif
                    </li>

                </ul>
            </nav>
            @endif

        </div>
    </div>
</div>

@endsection