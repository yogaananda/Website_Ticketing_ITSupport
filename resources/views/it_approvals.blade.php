@extends('layouts.layout')

@section('title', 'Persetujuan (Approval)')

@section('content')


<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 border-b border-gray-200 pb-5">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Persetujuan & Transaksi</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola permintaan masuk terbaru dari karyawan.</p>
        </div>
    </div>
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex gap-6">
            <a href="{{ url()->current() }}?tab=assets" 
               class="py-4 px-1 inline-flex items-center gap-2 border-b-2 text-sm whitespace-nowrap transition-colors
               {{ $activeTab == 'assets' 
                   ? 'border-indigo-600 text-indigo-600 font-semibold' 
                   : 'border-transparent text-gray-500 hover:text-indigo-600 hover:border-gray-300' 
               }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                Peminjaman Aset
                @if($countAssets > 0)
                    <span class="ml-1 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium {{ $activeTab == 'assets' ? 'bg-indigo-100 text-indigo-600' : 'bg-red-100 text-red-800' }}">
                        {{ $countAssets }}
                    </span>
                @endif
            </a>
            <a href="{{ url()->current() }}?tab=consumables" 
               class="py-4 px-1 inline-flex items-center gap-2 border-b-2 text-sm whitespace-nowrap transition-colors
               {{ $activeTab == 'consumables' 
                   ? 'border-indigo-600 text-indigo-600 font-semibold' 
                   : 'border-transparent text-gray-500 hover:text-indigo-600 hover:border-gray-300' 
               }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                Permintaan Barang
                @if($countConsumables > 0)
                    <span class="ml-1 inline-flex items-center py-0.5 px-1.5 rounded-full text-xs font-medium {{ $activeTab == 'consumables' ? 'bg-indigo-100 text-indigo-600' : 'bg-red-100 text-red-800' }}">
                        {{ $countConsumables }}
                    </span>
                @endif
            </a>

        </nav>
    </div>

    <div class="mt-3">
        @if($activeTab == 'assets')
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-fade-in">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aset</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($assetLoans as $loan)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $loan->user->full_name }}</div>
                                <div class="text-xs text-gray-500">{{ $loan->user->division ?? 'Staff' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-semibold">{{ $loan->asset->name }}</div>
                                <div class="text-xs text-gray-400 font-mono">{{ $loan->asset->code }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <div class="flex flex-col">
                                    <span>Pinjam: {{ $loan->loan_date->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-400">Kembali: {{ $loan->due_date->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                @php
                                    $statusClass = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'active' => 'bg-blue-100 text-blue-800',
                                        'returned' => 'bg-gray-100 text-gray-800',
                                        'rejected' => 'bg-red-100 text-red-800',
                                        'overdue' => 'bg-red-100 text-red-800 font-bold'
                                    ];
                                    $statusText = [
                                        'pending' => 'Menunggu',
                                        'active' => 'Dipinjam',
                                        'returned' => 'Selesai',
                                        'rejected' => 'Ditolak',
                                        'overdue' => 'Telat!'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-medium {{ $statusClass[$loan->status] ?? 'bg-gray-100' }}">
                                    {{ $statusText[$loan->status] ?? $loan->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-2">
                                    @if($loan->status == 'pending')
                                        <form action="{{ route('it.approvals.asset.approve', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Setujui" onclick="return confirm('Yakin ACC peminjaman ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('it.approvals.asset.reject', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Tolak" onclick="return confirm('Tolak peminjaman ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @elseif($loan->status == 'active' || $loan->status == 'overdue')
                                        <form action="{{ route('it.approvals.asset.return', $loan->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="px-3 py-1.5 text-xs font-medium text-gray-600 bg-gray-100 hover:bg-gray-200 rounded-lg border border-gray-200 transition-colors" onclick="return confirm('Barang sudah dikembalikan?')">
                                                Terima Kembali
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-gray-300">-</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada data peminjaman aset.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @if($activeTab == 'consumables')
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden animate-fade-in">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Karyawan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barang</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Jumlah</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Keperluan</th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($consumableRequests as $req)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $req->user->full_name }}</div>
                                <div class="text-xs text-gray-400">{{ $req->created_at->format('d M H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                {{ $req->consumable->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-50 text-indigo-700">
                                    {{ $req->amount }} {{ $req->consumable->unit }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 italic max-w-xs truncate">
                                "{{ $req->reason }}"
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex justify-center gap-2">
                                    @if($req->status == 'pending')
                                        <form action="{{ route('it.approvals.consumable.approve', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors" title="Setujui & Potong Stok" onclick="return confirm('Setujui permintaan ini? Stok akan berkurang.')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </button>
                                        </form>
                                        <form action="{{ route('it.approvals.consumable.reject', $req->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors" title="Tolak" onclick="return confirm('Tolak permintaan ini?')">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                            </button>
                                        </form>
                                    @elseif($req->status == 'approved')
                                        <span class="text-green-600 text-xs font-bold flex items-center gap-1 bg-green-50 px-2 py-1 rounded">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Approved
                                        </span>
                                    @else
                                        <span class="text-red-500 text-xs font-bold bg-red-50 px-2 py-1 rounded">Ditolak</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada permintaan barang.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection