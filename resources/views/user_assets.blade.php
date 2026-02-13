@extends('layouts.layout')

@section('title', 'Peminjaman Aset IT')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">

    {{-- 1. HEADER & TOMBOL AJUKAN --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Aset & Peminjaman Saya</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola perangkat IT yang sedang Anda gunakan atau ajukan baru.</p>
        </div>
        <button type="button" data-modal-target="modal-loan-request" data-modal-toggle="modal-loan-request" 
            class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <svg class="flex-shrink-0 w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Ajukan Peminjaman
        </button>
    </div>

    {{-- 2. GRID KARTU ASET --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        @forelse($myLoans as $loan)
        <div class="group flex flex-col bg-white border border-gray-200 shadow-sm rounded-2xl hover:shadow-xl transition-all duration-300 overflow-hidden">
            
            {{-- Bagian Gambar & Status Badge --}}
            <div class="h-52 bg-gray-100 relative flex items-center justify-center overflow-hidden">
                @if($loan->asset->image_path)
                    <img class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" src="{{ asset('storage/' . $loan->asset->image_path) }}" alt="{{ $loan->asset->name }}">
                @else
                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                @endif

                {{-- Status Badge (Absolute Top Right) --}}
                <div class="absolute top-3 right-3">
                    @php
                        $statusStyles = [
                            'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                            'active' => 'bg-blue-100 text-blue-800 border-blue-200',
                            'returned' => 'bg-gray-100 text-gray-800 border-gray-200',
                            'overdue' => 'bg-red-100 text-red-800 border-red-200',
                            'rejected' => 'bg-red-50 text-red-600 border-red-100',
                        ];
                        $statusLabel = [
                            'pending' => 'Menunggu Approval',
                            'active' => 'Sedang Dipinjam',
                            'returned' => 'Sudah Dikembalikan',
                            'overdue' => 'Terlambat',
                            'rejected' => 'Ditolak',
                        ];
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border shadow-sm {{ $statusStyles[$loan->status] }}">
                        {{ $statusLabel[$loan->status] }}
                    </span>
                </div>
            </div>

            {{-- Bagian Informasi Card --}}
            <div class="p-6 flex flex-col flex-1">
                
                {{-- Judul Aset --}}
                <div class="mb-4">
                    <h3 class="text-lg font-bold text-gray-900 group-hover:text-indigo-600 transition-colors">
                        {{ $loan->asset->name }}
                    </h3>
                    <p class="text-xs font-mono text-gray-500 bg-gray-100 px-2 py-0.5 rounded w-fit mt-1">
                        {{ $loan->asset->code }}
                    </p>
                </div>

                {{-- Detail Tanggal --}}
                <div class="space-y-3 mb-5 border-t border-gray-100 pt-4">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Tgl Pinjam
                        </span>
                        <span class="font-semibold text-gray-900">{{ \Carbon\Carbon::parse($loan->loan_date)->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-500 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Jatuh Tempo
                        </span>
                        <span class="font-semibold {{ $loan->status == 'overdue' ? 'text-red-600 animate-pulse' : 'text-gray-900' }}">
                            {{ \Carbon\Carbon::parse($loan->due_date)->format('d M Y') }}
                        </span>
                    </div>
                </div>

                {{-- Notes / Keperluan --}}
                <div class="mt-auto">
                    <p class="text-xs text-gray-500 italic bg-gray-50 p-3 rounded-lg border border-gray-100 line-clamp-2">
                        "{{ $loan->notes }}"
                    </p>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full py-16 flex flex-col items-center justify-center text-center bg-white border border-dashed border-gray-300 rounded-2xl">
            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-gray-900">Belum ada peminjaman</h3>
            <p class="text-gray-500 text-sm max-w-sm mt-1">Anda belum meminjam perangkat apapun. Klik tombol di atas untuk mengajukan.</p>
        </div>
        @endforelse

    </div>

</div>

{{-- ========================================== --}}
{{-- MODAL FORM PENGAJUAN (NO BLUR) --}}
{{-- ========================================== --}}
<div id="modal-loan-request" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            
            {{-- Header Modal --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Form Peminjaman Aset</h3>
                    <p class="text-xs text-gray-500 mt-1">Isi formulir untuk mengajukan perangkat.</p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors" data-modal-hide="modal-loan-request">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>

            {{-- Form Request --}}
            <form action="{{ route('user.assets.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    
                    {{-- 1. Pilih Barang --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Pilih Perangkat (Status Ready)</label>
                        <select name="asset_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($availableAssets as $asset)
                                <option value="{{ $asset->id }}">
                                    {{ $asset->name }} ({{ $asset->code }}) - Kondisi: {{ ucfirst($asset->condition) }}
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-[10px] text-gray-500">Hanya menampilkan aset yang tersedia di gudang.</p>
                    </div>

                    {{-- 2. Tanggal Pinjam & Kembali --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Tanggal Mulai</label>
                            <input type="date" name="loan_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Rencana Kembali</label>
                            <input type="date" name="due_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                    </div>

                    {{-- 3. Keperluan --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Keperluan / Catatan</label>
                        <textarea name="notes" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Untuk project development modul HR..." required></textarea>
                    </div>

                </div>

                {{-- Footer Modal --}}
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-loan-request" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">Kirim Pengajuan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection