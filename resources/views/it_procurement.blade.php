@extends('layouts.layout')

@section('title', 'Request Pembelian')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-200 pb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Request Pembelian (Procurement)</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola dan ajukan pembelian aset baru atau kebutuhan operasional IT.</p>
        </div>
        <button type="button" data-modal-target="modal-add-procurement" data-modal-toggle="modal-add-procurement" 
            class="group py-2.5 px-5 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 disabled:opacity-50 disabled:pointer-events-none shadow-sm hover:shadow-md transition-all">
            <svg class="flex-shrink-0 w-4 h-4 transition-transform group-hover:rotate-90" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" x2="12" y1="5" y2="19"/><line x1="5" x2="19" y1="12" y2="12"/></svg>
            Buat Pengajuan Baru
        </button>
    </div>
    <div class="flex flex-col">
        <div class="-m-1.5 overflow-x-auto">
            <div class="p-1.5 min-w-full inline-block align-middle">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 grid gap-3 md:flex md:justify-between md:items-center border-b border-gray-200 bg-gray-50/50">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Daftar Pengajuan</h2>
                            <p class="text-sm text-gray-500">Riwayat pengajuan pembelian barang.</p>
                        </div>
                    </div>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">Barang / Item</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex justify-center items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">Qty</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-start">
                                    <div class="flex items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">Estimasi & Prioritas</span>
                                    </div>
                                </th>
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex justify-center items-center gap-x-2">
                                        <span class="text-xs font-semibold uppercase tracking-wide text-gray-800">Status</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200">
                            @forelse($procurements as $item)
                            <tr class="bg-white hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col">
                                        <span class="block text-sm font-bold text-gray-800 mb-1">{{ $item->item_name }}</span>
                                        <span class="block text-xs text-gray-500 italic max-w-xs whitespace-normal line-clamp-2">
                                            {{ $item->description ?? 'Tidak ada deskripsi.' }}
                                        </span>
                                        
                                        @if($item->link_reference)
                                            <a href="{{ $item->link_reference }}" target="_blank" class="mt-2 inline-flex items-center gap-x-1.5 text-xs text-blue-600 decoration-2 hover:underline font-medium">
                                                <svg class="flex-shrink-0 w-3 h-3" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"/></svg>
                                                Link Referensi
                                            </a>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center align-top">
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-lg text-xs font-medium bg-gray-100 text-gray-800 shadow-sm border border-gray-200">
                                        {{ $item->quantity }} Units
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap align-top">
                                    <div class="flex flex-col gap-2">
                                        <div class="text-sm font-medium text-gray-800">
                                            @if($item->estimated_price)
                                                Rp {{ number_format($item->estimated_price, 0, ',', '.') }}
                                            @else
                                                <span class="text-gray-400 italic">Tanpa Estimasi</span>
                                            @endif
                                        </div>
                                        <div>
                                            @php
                                                $prioClass = [
                                                    'low'      => 'bg-gray-100 text-gray-600 border-gray-200',
                                                    'medium'   => 'bg-blue-50 text-blue-600 border-blue-100',
                                                    'high'     => 'bg-orange-50 text-orange-600 border-orange-100',
                                                    'critical' => 'bg-red-50 text-red-600 border-red-100 font-bold',
                                                ];
                                                $prioLabel = [
                                                    'low'      => 'Low Priority',
                                                    'medium'   => 'Medium Priority',
                                                    'high'     => 'High Priority',
                                                    'critical' => 'Critical',
                                                ];
                                            @endphp
                                            <span class="inline-flex items-center gap-1.5 py-0.5 px-2 rounded-md text-[10px] uppercase tracking-wide border {{ $prioClass[$item->priority] }}">
                                                <span class="w-1.5 h-1.5 rounded-full {{ str_replace(['bg-', 'text-'], 'bg-', $prioClass[$item->priority]) }} bg-current"></span>
                                                {{ $prioLabel[$item->priority] }}
                                            </span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center align-top">
                                    @php
                                        $statusClass = [
                                            'pending'   => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                            'approved'  => 'bg-teal-100 text-teal-800 border-teal-200',
                                            'rejected'  => 'bg-red-100 text-red-800 border-red-200',
                                            'purchased' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
                                        ];
                                        $statusIcon = [
                                            'pending'   => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>',
                                            'approved'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>',
                                            'rejected'  => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>',
                                            'purchased' => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium border shadow-sm {{ $statusClass[$item->status] }}">
                                        {!! $statusIcon[$item->status] !!}
                                        {{ ucfirst($item->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="flex flex-col justify-center items-center">
                                        <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3">
                                            <svg class="w-6 h-6 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                        </div>
                                        <h3 class="text-gray-500 text-sm font-medium">Belum ada pengajuan pembelian.</h3>
                                        <p class="text-gray-400 text-xs mt-1">Klik tombol di atas untuk membuat pengajuan baru.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="px-6 py-4 border-t border-gray-200">
                        {{ $procurements->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
<div id="modal-add-procurement" tabindex="-1" aria-hidden="true" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm p-4 transition-opacity">
    
    <div class="relative w-full max-w-4xl bg-white rounded-2xl shadow-2xl border border-gray-100 overflow-hidden flex flex-col max-h-[90vh]">
        <div class="flex items-center justify-between p-5 border-b border-gray-100 bg-gray-50/80 shrink-0">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Form Pengajuan Pembelian</h3>
                <p class="text-xs text-gray-500 mt-0.5">Isi detail barang yang dibutuhkan dengan lengkap.</p>
            </div>
            <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="modal-add-procurement">
                <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
            </button>
        </div>
        <form id="form-create-procurement" action="{{ route('it.procurements.store') }}" method="POST" class="flex flex-col flex-1 overflow-hidden">
            @csrf
            @if($errors->any())
                <div class="mx-6 mt-4 p-3 bg-red-100 border border-red-200 text-red-700 rounded-lg text-sm">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="p-6 overflow-y-auto space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="md:col-span-3">
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="item_name" value="{{ old('item_name') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: SSD NVMe 1TB" required>
                    </div>
                    
                    <div class="md:col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-900">ID Tiket <span class="text-gray-400 font-normal text-xs">(Opsional)</span></label>
                        <input type="number" name="ticket_id" value="{{ old('ticket_id') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="105">
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Jumlah <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Est. Harga (Rp)</label>
                        <input type="number" name="estimated_price" value="{{ old('estimated_price') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="0">
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-900">Link Pembelian</label>
                        <input type="url" name="link_reference" value="{{ old('link_reference') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="https://...">
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-900">Tingkat Prioritas <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach(['low', 'medium', 'high', 'critical'] as $prio)
                        <label class="cursor-pointer">
                            <input type="radio" name="priority" value="{{ $prio }}" class="peer sr-only" {{ old('priority', 'medium') == $prio ? 'checked' : '' }}>
                            <div class="rounded-lg border border-gray-200 bg-white p-3 text-center text-sm font-medium text-gray-500 transition-all hover:bg-gray-50 peer-checked:border-indigo-600 peer-checked:bg-indigo-50 peer-checked:text-indigo-600">
                                {{ ucfirst($prio) }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div>
                    <label class="block mb-2 text-sm font-semibold text-gray-900">Spesifikasi Detail</label>
                    <textarea name="description" rows="2" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 resize-none">{{ old('description') }}</textarea>
                </div>

            </div>
            <div class="flex items-center justify-end gap-3 p-5 border-t border-gray-100 bg-gray-50/80 shrink-0">
                <button data-modal-hide="modal-add-procurement" type="button" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                <button type="submit" form="form-create-procurement" class="px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 shadow-sm transition-all transform active:scale-95">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

@endsection