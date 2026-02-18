@extends('layouts.layout')

@section('title', 'Stok Logistik')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Stok Logistik IT</h2>
            <p class="text-sm text-gray-500">Kelola barang habis pakai (Tinta, Kertas, Kabel, dll).</p>
        </div>
        <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
            <form action="{{ route('admin.consumables.index') }}" method="GET" class="relative w-full sm:w-64">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" 
                    class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-indigo-500 focus:border-indigo-500" 
                    placeholder="Cari barang...">
            </form>
            <button type="button" data-modal-target="modal-add-consumable" data-modal-toggle="modal-add-consumable" 
                class="text-white bg-indigo-600 hover:bg-indigo-700 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center justify-center gap-2 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Stok
            </button>
        </div>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Nama Barang</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Kategori & Lokasi</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Stok</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Satuan</th>
                        <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @forelse($consumables as $item)
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-gray-100 border border-gray-200 flex items-center justify-center overflow-hidden shrink-0">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-gray-900">{{ $item->name }}</div>
                                    <div class="text-xs text-gray-500">Updated: {{ $item->updated_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col gap-1">
                                <span class="inline-flex items-center w-fit px-2 py-0.5 rounded text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                    {{ $item->category ?? 'Umum' }}
                                </span>
                                <div class="flex items-center gap-1 text-xs text-gray-500">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $item->location ?? '-' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            @php
                                $isLow = $item->stock <= $item->min_stock;
                                $stockColor = $isLow ? 'bg-red-100 text-red-800 border-red-200' : 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            @endphp
                            
                            <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-bold border shadow-sm {{ $stockColor }}">
                                {{ $item->stock }}
                            </span>
                            
                            @if($isLow)
                                <div class="text-[10px] text-red-500 mt-1 font-medium animate-pulse">
                                    Stok Menipis!
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $item->unit }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" 
                                    data-modal-target="modal-edit-{{ $item->id }}" 
                                    data-modal-toggle="modal-edit-{{ $item->id }}"
                                    class="group p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-all duration-200"
                                    title="Edit Data">
                                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <form action="{{ route('admin.consumables.destroy', $item->id) }}" method="POST" 
                                    onsubmit="return confirm('Apakah Anda yakin ingin menghapus stok {{ $item->name }}? Data tidak bisa dikembalikan.');"
                                    class="inline-block">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="group p-2 text-red-500 hover:bg-red-50 rounded-lg transition-all duration-200"
                                        title="Hapus Data">
                                        <svg class="w-5 h-5 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">
                            Tidak ada data stok ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-normal text-gray-500">
                Showing <span class="font-semibold text-gray-900">{{ $consumables->firstItem() ?? 0 }}</span> 
                to <span class="font-semibold text-gray-900">{{ $consumables->lastItem() ?? 0 }}</span> 
                of <span class="font-semibold text-gray-900">{{ $consumables->total() }}</span> results
            </div>
            @if ($consumables->hasPages())
                {{ $consumables->appends(request()->query())->links('pagination::tailwind') }}
            @endif
        </div>
    </div>
</div>
<div id="modal-add-consumable" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Tambah Stok Baru</h3>
                    <p class="text-xs text-gray-500 mt-1">Isi detail barang logistik di bawah ini.</p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition-colors" data-modal-hide="modal-add-consumable">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <form action="{{ route('admin.consumables.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="p-6 space-y-5">
                    
                    {{-- Nama Barang --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Nama Barang</label>
                        <input type="text" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 transition-all" placeholder="Contoh: Tinta Epson 664 Hitam" required>
                    </div>

                    {{-- Grid 2 Kolom --}}
                    <div class="grid grid-cols-2 gap-5">
                        {{-- Kategori --}}
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Kategori</label>
                            <select name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5 cursor-pointer">
                                <option value="ATK">ATK (Alat Tulis)</option>
                                <option value="Tinta">Tinta / Toner</option>
                                <option value="Sparepart">Sparepart IT</option>
                                <option value="Kebersihan">Alat Kebersihan</option>
                                <option value="Lainnya">Lainnya</option>
                            </select>
                        </div>
                        {{-- Lokasi --}}
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Lokasi Rak</label>
                            <input type="text" name="location" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: Lemari B2">
                        </div>
                    </div>

                    {{-- Grid 3 Kolom untuk Angka --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Stok Awal</label>
                            <input type="number" name="stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="0" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Satuan</label>
                            <input type="text" name="unit" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Pcs/Rim" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Min. Alert</label>
                            <input type="number" name="min_stock" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" value="5" required>
                        </div>
                    </div>

                    {{-- Upload Foto Cantik --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Foto Barang (Opsional)</label>
                        <div class="flex items-center justify-center w-full">
                            <label for="dropzone-file-add" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition-all">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                    <svg class="w-8 h-8 mb-3 text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                    </svg>
                                    <p class="text-xs text-gray-500"><span class="font-semibold">Klik untuk upload</span> atau drag file</p>
                                    <p class="text-[10px] text-gray-400 mt-1">SVG, PNG, JPG (MAX. 2MB)</p>
                                </div>
                                <input id="dropzone-file-add" name="image" type="file" class="hidden" />
                            </label>
                        </div>
                    </div>

                </div>

                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-add-consumable" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-gray-100 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 shadow-sm transition-all transform active:scale-95">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ========================================== --}}
{{-- 2. MODAL EDIT STOK (TANPA BLUR) --}}
{{-- ========================================== --}}
@foreach($consumables as $item)
{{-- Perhatikan class di bawah: 'backdrop-blur-sm' sudah dihapus --}}
<div id="modal-edit-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/30">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            
            {{-- Header Edit --}}
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Edit Stok</h3>
                    <p class="text-xs text-gray-500 mt-1">Update data: <span class="font-medium text-indigo-600">{{ $item->name }}</span></p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-red-50 hover:text-red-600 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="modal-edit-{{ $item->id }}">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>

            {{-- Form Edit --}}
            <form action="{{ route('admin.consumables.update', $item->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="p-6 space-y-5">
                    
                    {{-- Nama --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Nama Barang</label>
                        <input type="text" name="name" value="{{ $item->name }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    </div>

                    {{-- Grid Kategori & Lokasi --}}
                    <div class="grid grid-cols-2 gap-5">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Kategori</label>
                            <select name="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                                <option value="ATK" {{ $item->category == 'ATK' ? 'selected' : '' }}>ATK</option>
                                <option value="Tinta" {{ $item->category == 'Tinta' ? 'selected' : '' }}>Tinta / Toner</option>
                                <option value="Sparepart" {{ $item->category == 'Sparepart' ? 'selected' : '' }}>Sparepart IT</option>
                                <option value="Kebersihan" {{ $item->category == 'Kebersihan' ? 'selected' : '' }}>Kebersihan</option>
                                <option value="Lainnya" {{ $item->category == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Lokasi</label>
                            <input type="text" name="location" value="{{ $item->location }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                        </div>
                    </div>

                    {{-- Grid Stok --}}
                    <div class="grid grid-cols-3 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Stok</label>
                            <input type="number" name="stock" value="{{ $item->stock }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Satuan</label>
                            <input type="text" name="unit" value="{{ $item->unit }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-900">Min. Alert</label>
                            <input type="number" name="min_stock" value="{{ $item->min_stock }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                        </div>
                    </div>

                    {{-- Upload Foto Edit --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Ganti Foto (Opsional)</label>
                        <div class="flex gap-4 items-center">
                            @if($item->image_path)
                                <div class="w-16 h-16 rounded-lg border border-gray-200 overflow-hidden shrink-0">
                                    <img src="{{ asset('storage/' . $item->image_path) }}" class="w-full h-full object-cover">
                                </div>
                            @endif
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" name="image" type="file">
                        </div>
                   </div>

                </div>

                {{-- Footer Edit --}}
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-edit-{{ $item->id }}" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-indigo-600 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
@endsection