@extends('layouts.layout')

@section('title', 'Persetujuan Admin')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-8">
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Pusat Persetujuan (Approval)</h2>
        <p class="text-sm text-gray-500">Tinjau pengajuan dari User dan IT Support.</p>
    </div>
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        
        <div class="px-6 py-4 border-b border-gray-200 bg-indigo-50/50 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-bold text-indigo-900 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    Request Pembelian Barang (IT Support)
                </h3>
                <p class="text-xs text-gray-500">Menunggu persetujuan anggaran/pembelian.</p>
            </div>
            @if($procurements->count() > 0)
                <span class="inline-flex items-center py-1 px-2 rounded-full text-xs font-medium bg-red-100 text-red-800">
                    {{ $procurements->count() }} Pending
                </span>
            @endif
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelapor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detail Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estimasi Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Urgensi</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($procurements as $item)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-xs">
                                    {{ substr($item->user->full_name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900">{{ $item->user->full_name }}</div>
                                    <div class="text-xs text-gray-500">IT Support</div>
                                    <div class="text-[10px] text-gray-400 mt-0.5">{{ $item->created_at->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 align-top">
                            <div class="text-sm font-bold text-gray-900">{{ $item->item_name }}</div>
                            <div class="text-xs text-gray-600 mt-1 max-w-xs leading-relaxed">
                                "{{ $item->description }}"
                            </div>
                            <div class="flex items-center gap-3 mt-2">
                                <span class="text-xs font-mono bg-gray-100 px-2 py-0.5 rounded text-gray-600">Qty: {{ $item->quantity }}</span>
                                @if($item->link_reference)
                                    <a href="{{ $item->link_reference }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center gap-1">
                                        Link Produk <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    </a>
                                @endif
                            </div>
                            @if($item->ticket)
                                <div class="mt-2 inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] bg-yellow-50 text-yellow-700 border border-yellow-100">
                                    Ref: Tiket #{{ $item->ticket->ticket_code }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-900">Rp {{ number_format($item->estimated_price, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">per unit</div>
                            @if($item->quantity > 1)
                                <div class="text-xs font-semibold text-gray-700 mt-1 pt-1 border-t border-gray-200">
                                    Total: Rp {{ number_format($item->estimated_price * $item->quantity, 0, ',', '.') }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 align-top whitespace-nowrap">
                            @php
                                $prioColor = match($item->priority) {
                                    'critical' => 'bg-red-100 text-red-800 border-red-200',
                                    'high' => 'bg-orange-100 text-orange-800 border-orange-200',
                                    'medium' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'low' => 'bg-gray-100 text-gray-800 border-gray-200',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $prioColor }}">
                                {{ ucfirst($item->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 align-top text-center">
                            <div class="flex flex-col gap-2">
                                <form action="{{ route('admin.approvals.procurement.approve', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" onclick="return confirm('Yakin setujui pembelian ini?')" class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-xs font-bold rounded-lg border border-transparent bg-green-600 text-white hover:bg-green-700 transition-colors shadow-sm">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                        Setujui
                                    </button>
                                </form>
                                <button type="button" data-modal-target="modal-reject-{{ $item->id }}" data-modal-toggle="modal-reject-{{ $item->id }}" 
                                    class="w-full py-2 px-3 inline-flex justify-center items-center gap-x-2 text-xs font-bold rounded-lg border border-gray-200 bg-white text-red-600 shadow-sm hover:bg-red-50 hover:border-red-200 transition-colors">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                    Tolak
                                </button>
                            </div>
                            <div id="modal-reject-{{ $item->id }}" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4 text-left">
                                <div class="relative w-full max-w-sm bg-white rounded-xl shadow-lg">
                                    <form action="{{ route('admin.approvals.procurement.reject', $item->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="p-5">
                                            <h3 class="text-lg font-bold text-gray-900 mb-2">Tolak Pengajuan?</h3>
                                            <p class="text-sm text-gray-500 mb-4">Berikan alasan kenapa pengajuan barang <strong>{{ $item->item_name }}</strong> ini ditolak.</p>
                                            
                                            <textarea name="admin_note" rows="3" class="w-full border-gray-300 rounded-lg text-sm focus:ring-red-500 focus:border-red-500" placeholder="Contoh: Anggaran bulan ini habis / Stok di gudang masih ada" required></textarea>
                                        </div>
                                        <div class="flex justify-end gap-2 p-4 border-t bg-gray-50 rounded-b-xl">
                                            <button type="button" data-modal-hide="modal-reject-{{ $item->id }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                                            <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700">Tolak Pengajuan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Tidak ada pengajuan pembelian baru saat ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@endsection