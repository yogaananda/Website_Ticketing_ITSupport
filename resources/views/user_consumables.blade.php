@extends('layouts.layout')

@section('title', 'Permintaan Barang')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row justify-between items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Permintaan Barang (ATK)</h2>
            <p class="text-sm text-gray-500 mt-1">Ajukan permintaan barang habis pakai seperti kertas, tinta, dll.</p>
        </div>
        <button type="button" data-modal-target="modal-request-item" data-modal-toggle="modal-request-item" 
            class="py-3 px-5 inline-flex items-center gap-x-2 text-sm font-medium rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Minta Barang Baru
        </button>
    </div>

    {{-- TABEL --}}
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Alasan / Keperluan</th> {{-- Ubah Header --}}
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($myRequests as $req)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $req->created_at->format('d M Y') }}
                            <div class="text-xs text-gray-400">{{ $req->created_at->format('H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">{{ $req->consumable->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                {{ $req->amount }} {{ $req->consumable->unit }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            @if($req->status == 'pending')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Menunggu</span>
                            @elseif($req->status == 'approved')
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Disetujui</span>
                            @else
                                <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Ditolak</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 italic">
                            "{{ $req->reason ?? '-' }}" {{-- Panggil REASON --}}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada riwayat permintaan.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $myRequests->links() }}
        </div>
    </div>
</div>

{{-- MODAL FORM --}}
<div id="modal-request-item" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full bg-gray-900/50 backdrop-blur-sm">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/50">
                <h3 class="text-lg font-bold text-gray-900">Form Permintaan Barang</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="modal-request-item">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 14 14"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="{{ route('user.consumables.store') }}" method="POST">
                @csrf
                <div class="p-6 space-y-5">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Pilih Barang</label>
                        <select name="consumable_id" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                            <option value="">-- Pilih Barang --</option>
                            @foreach($consumables as $item)
                                <option value="{{ $item->id }}">{{ $item->name }} (Sisa: {{ $item->stock }} {{ $item->unit }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Jumlah</label>
                        <input type="number" name="amount" min="1" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    </div>
                    {{-- Input REASON --}}
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Alasan / Keperluan</label>
                        <textarea name="reason" rows="3" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Contoh: Untuk keperluan meeting..."></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/50 rounded-b-2xl">
                    <button data-modal-hide="modal-request-item" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection