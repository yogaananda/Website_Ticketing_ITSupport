@extends('layouts.layout')

@section('title', 'Manajemen Aset IT')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Inventaris Aset IT</h2>
            <p class="text-sm text-gray-500">Kelola aset permanen (PC, Laptop, Monitor, dll).</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <form action="{{ route('admin.assets.index') }}" method="GET" class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Cari SN, Kode, atau Nama...">
            </form>
            <button type="button" data-modal-target="modal-add-asset" data-modal-toggle="modal-add-asset" class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center gap-2 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Aset
            </button>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-left">Informasi Aset</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-left">Status & Kondisi</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-left">Pemegang</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase text-center">Aksi</th>
                    </tr>
                </thead>
                
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($assets as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-gray-100 border flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500">
                                        <span class="font-mono bg-gray-100 px-1 rounded">{{ $item->code }}</span> 
                                        @if($item->serial_number) | SN: {{ $item->serial_number }} @endif
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-2">
                                @php
                                    $statusLabel = [
                                        'ready' => 'Tersedia',
                                        'in_use' => 'Digunakan',
                                        'lost' => 'Hilang'
                                    ];
                                @endphp
                                <span class="w-fit px-2.5 py-0.5 rounded-full text-[10px] font-bold border uppercase {{ $item->statusColor }}">
                                    {{ $statusLabel[$item->status] ?? $item->status }}
                                </span>
                                <div class="flex items-center gap-1.5 text-xs font-medium text-gray-600">
                                    @if($item->condition == 'good')
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Kondisi Bagus
                                    @elseif($item->condition == 'maintenance')
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Sedang Perbaikan
                                    @else
                                        <span class="w-2 h-2 rounded-full bg-red-500"></span> Rusak
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($item->user)
                                <div class="text-sm font-medium text-gray-900">{{ $item->user->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $item->user->division ?? 'Karyawan' }}</div>
                            @else
                                <span class="text-xs text-gray-400 italic flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                    Gudang IT
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" data-modal-target="modal-edit-asset-{{ $item->id }}" data-modal-toggle="modal-edit-asset-{{ $item->id }}" class="group p-2 text-indigo-500 hover:bg-indigo-50 rounded-lg transition-all" title="Edit Aset">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </button>
                                <button type="button" data-modal-target="modal-history-asset-{{ $item->id }}" data-modal-toggle="modal-history-asset-{{ $item->id }}" class="group p-2 text-blue-500 hover:bg-blue-50 rounded-lg transition-all" title="Riwayat Kerusakan (Tiket)">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </button>
                                <form action="{{ route('admin.assets.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus aset {{ $item->name }}?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="group p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all" title="Hapus Aset">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">
                            Belum ada data aset yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm text-gray-500">
                Showing <span class="font-semibold text-gray-900">{{ $assets->firstItem() ?? 0 }}</span> 
                to <span class="font-semibold text-gray-900">{{ $assets->lastItem() ?? 0 }}</span> 
                of <span class="font-semibold text-gray-900">{{ $assets->total() }}</span> results
            </div>
            @if($assets->hasPages()) 
                {{ $assets->appends(request()->query())->links('pagination::tailwind') }} 
            @endif
        </div>
    </div>
</div>
<div id="modal-add-asset" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Tambah Aset Baru</h3>
                    <p class="text-xs text-gray-500 mt-1">Isi data aset secara lengkap di bawah ini.</p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors" data-modal-hide="modal-add-asset">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.assets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Informasi Fisik</h4>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Nama Barang <span class="text-red-500">*</span></label>
                            <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: Laptop Dell Latitude 5400" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Kode Aset <span class="text-red-500">*</span></label>
                                <input type="text" name="code" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="AST-001" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Serial Number</label>
                                <input type="text" name="serial_number" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="SN123...">
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Lokasi Fisik</label>
                                <input type="text" name="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Rak A1">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Tgl Pembelian</label>
                                <input type="date" name="purchase_date" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Status & Kelengkapan</h4>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Kondisi</label>
                                <select name="condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                    <option value="good">Bagus (Good)</option>
                                    <option value="maintenance">Perbaikan</option>
                                    <option value="broken">Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Status</label>
                                <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                    <option value="ready">Tersedia (Ready)</option>
                                    <option value="in_use">Digunakan</option>
                                    <option value="lost">Hilang</option>
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Pemegang Aset (User)</label>
                            <select name="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="">-- Tidak Ada (Simpan di Gudang) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->full_name }} ({{ $user->division }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Foto Aset</label>
                            <div class="flex items-center justify-center w-full">
                                <label for="dropzone-file-asset-add" class="flex flex-col items-center justify-center w-full h-28 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                    <div class="flex flex-col items-center justify-center pt-2 pb-3">
                                        <svg class="w-6 h-6 mb-2 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/></svg>
                                        <p class="text-xs text-gray-500"><span class="font-semibold">Klik Upload</span> / Drag file</p>
                                    </div>
                                    <input id="dropzone-file-asset-add" name="image" type="file" class="hidden" />
                                </label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-add-asset" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">Simpan Aset</button>
                </div>
            </form>
        </div>
    </div>
</div>
@foreach($assets as $item)
<div id="modal-edit-asset-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Edit Aset IT</h3>
                    <p class="text-xs text-gray-500 mt-1">Update data untuk: <span class="text-indigo-600 font-bold">{{ $item->code }}</span></p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="modal-edit-asset-{{ $item->id }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>

            <form action="{{ route('admin.assets.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="space-y-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Informasi Fisik</h4>
                        
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Nama Barang</label>
                            <input type="text" name="name" value="{{ $item->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Kode Aset</label>
                                <input type="text" name="code" value="{{ $item->code }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Serial Number</label>
                                <input type="text" name="serial_number" value="{{ $item->serial_number }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Lokasi Fisik</label>
                                <input type="text" name="location" value="{{ $item->location }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Tgl Pembelian</label>
                                <input type="date" name="purchase_date" value="{{ $item->purchase_date }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            </div>
                        </div>
                    </div>
                    <div class="space-y-5">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider border-b border-gray-100 pb-2">Status & Kelengkapan</h4>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Kondisi</label>
                                <select name="condition" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                    <option value="good" {{ $item->condition == 'good' ? 'selected' : '' }}>Bagus</option>
                                    <option value="maintenance" {{ $item->condition == 'maintenance' ? 'selected' : '' }}>Perbaikan</option>
                                    <option value="broken" {{ $item->condition == 'broken' ? 'selected' : '' }}>Rusak</option>
                                </select>
                            </div>
                            <div>
                                <label class="block mb-2 text-sm font-semibold text-gray-900">Status</label>
                                <select name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                    <option value="ready" {{ $item->status == 'ready' ? 'selected' : '' }}>Ready</option>
                                    <option value="in_use" {{ $item->status == 'in_use' ? 'selected' : '' }}>In Use</option>
                                    <option value="lost" {{ $item->status == 'lost' ? 'selected' : '' }}>Lost</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Pemegang Aset (User)</label>
                            <select name="user_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="">-- Tidak Ada (Simpan di Gudang) --</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ $item->user_id == $user->id ? 'selected' : '' }}>{{ $user->full_name }} ({{ $user->division }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Ganti Foto</label>
                            <div class="flex items-center gap-3">
                                @if($item->image_path)
                                    <div class="shrink-0 w-16 h-16 border rounded-lg overflow-hidden bg-gray-50">
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <input type="file" name="image" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-edit-asset-{{ $item->id }}" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Riwayat Tiket / Kerusakan Aset -->
<div id="modal-history-asset-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-4xl max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Riwayat Kerusakan Aset</h3>
                    <p class="text-xs text-gray-500 mt-1">Rekam jejak pelaporan teknis untuk: <span class="text-indigo-600 font-bold">{{ $item->name }} ({{ $item->code }})</span></p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="modal-history-asset-{{ $item->id }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <div class="p-5 overflow-auto max-h-[60vh]">
                @if($item->tickets && $item->tickets->count() > 0)
                <div class="relative overflow-x-auto shadow-sm sm:rounded-lg border border-gray-200">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3">Tiket & Tgl</th>
                                <th scope="col" class="px-6 py-3">Pelapor</th>
                                <th scope="col" class="px-6 py-3">Kendala</th>
                                <th scope="col" class="px-6 py-3">Status</th>
                                <th scope="col" class="px-6 py-3">Teknisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($item->tickets as $ticket)
                            <tr class="bg-white border-b hover:bg-gray-50">
                                <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                                    <span class="text-indigo-600">#{{ $ticket->ticket_code }}</span><br>
                                    <span class="text-xs text-gray-400">{{ $ticket->created_at->format('d M Y') }}</span>
                                </td>
                                <td class="px-6 py-4">{{ $ticket->user->full_name ?? $ticket->user->username ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-gray-800">{{ Str::limit($ticket->title, 20) }}</div>
                                    <div class="text-xs text-gray-500">{{ Str::limit($ticket->description, 30) }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    @if($ticket->status == 'resolved')
                                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded border border-green-300">Selesai</span>
                                    @elseif($ticket->status == 'in_progress')
                                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded border border-blue-300">Diproses</span>
                                    @else
                                        <span class="bg-gray-100 text-gray-800 text-xs font-medium px-2.5 py-0.5 rounded border border-gray-300">Menunggu</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">{{ $ticket->technician->full_name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center p-8 border-2 border-dashed border-gray-200 rounded-xl bg-gray-50">
                    <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <h3 class="text-sm font-medium text-gray-900">Belum Ada Riwayat Kerusakan</h3>
                    <p class="text-xs text-gray-500 mt-1">Aset ini belum pernah dilaporkan rusak melalui sistem tiket.</p>
                </div>
                @endif
            </div>
            <div class="flex justify-end p-5 border-t border-gray-100 bg-gray-50/50">
                <button data-modal-hide="modal-history-asset-{{ $item->id }}" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endsection