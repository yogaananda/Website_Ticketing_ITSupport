@extends('layouts.layout')

@section('title', 'Jadwal Maintenance')

@section('content')
@if(session('success'))
<div class="mb-4 bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-lg relative flex items-center" role="alert">
    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
    <span class="block sm:inline">{{ session('success') }}</span>
</div>
@endif

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto space-y-6">
    <div class="max-w-7xl mx-auto p-6 font-sans">
        <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
            <div class="flex flex-col md:flex-row items-center justify-between p-6 bg-white border-b border-gray-100">
                <div class="mb-4 md:mb-0 text-center md:text-left">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Calendar Daily Activity</span>
                    <h2 class="text-3xl font-extrabold text-slate-800 mt-1">
                        {{ $currentMonthName }}
                    </h2>
                </div>
                
                <div class="flex items-center space-x-2">
                    <a href="{{ route('it.maintenance.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Today</a>
                    <div class="inline-flex rounded-md shadow-sm" role="group">
                        <a href="{{ route('it.maintenance.index', ['date' => $prevMonth]) }}" class="p-2 text-sm text-slate-600 border border-gray-300 rounded-l-lg hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </a>
                        <a href="{{ route('it.maintenance.index', ['date' => $nextMonth]) }}" class="p-2 text-sm text-slate-600 border-l-0 border border-gray-300 rounded-r-lg hover:bg-gray-50">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-7 border-b border-gray-200 bg-gray-50">
                @foreach(['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'] as $idx => $day)
                    <div class="py-3 text-center text-sm font-semibold tracking-wide border-r border-gray-200 last:border-r-0 {{ $idx === 0 || $idx === 6 ? 'text-red-500 bg-red-50/50' : 'text-slate-500' }}">
                        {{ $day }}
                    </div>
                @endforeach
            </div>
            <div class="grid grid-cols-7 bg-white">
                @for ($i = 0; $i < $firstDayOfWeek; $i++)
                    <div class="min-h-[120px] bg-gray-50/30 border-b border-r border-gray-100"></div>
                @endfor
                @foreach ($calendarDays as $dayData)
                    <div data-modal-target="default-modal" 
                         data-modal-toggle="default-modal"
                         onclick="openModalWithDate('{{ $dayData['full_date'] }}', '{{ \Carbon\Carbon::parse($dayData['full_date'])->format('d F Y') }}')"
                         class="group relative min-h-[120px] p-2 border-b border-r border-gray-100 hover:bg-slate-50 transition cursor-pointer {{ $dayData['bg_class'] }}">
                        
                        <div class="flex justify-between items-start">
                            <span class="text-sm font-semibold w-7 h-7 flex items-center justify-center rounded-full {{ $dayData['number_class'] }}">
                                {{ $dayData['day'] }}
                            </span>
                            <span class="opacity-0 group-hover:opacity-100 text-indigo-400 text-xs flex items-center">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add
                            </span>
                        </div>
                        <div class="mt-2 space-y-1">
                            @foreach($dayData['events'] as $event)
                                <div class="px-2 py-1 text-[10px] font-medium border rounded truncate {{ $event['color_class'] }} relative group/item flex items-center" onclick="event.stopPropagation()">
                                    <span class="shrink-0 mr-1">{!! $event['icon'] !!}</span>
                                    <span class="truncate">{{ $event['title'] }}</span>
                                    
                                    @if($event['type'] === 'appointment')
                                    <form action="{{ route('it.maintenance.destroy', $event['id']) }}" method="POST" class="absolute right-1 top-0.5 hidden group-hover/item:block bg-white/80 rounded px-1 shadow-sm border border-gray-200">
                                        @csrf @method('DELETE')
                                        <button onclick="return confirm('Hapus jadwal ini?')" class="text-red-500 hover:text-red-700 flex items-center justify-center w-4 h-4">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
                @for ($i = 0; $i < $remainingCells; $i++)
                    <div class="min-h-[120px] bg-gray-50/30 border-b border-r border-gray-100"></div>
                @endfor
            </div>
            
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-200 text-xs text-gray-500">
                Timezone: Asia/Jakarta
            </div>
        </div>
    </div>
</div>
<div id="default-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-lg max-h-full">
        <div class="relative bg-white border border-gray-200 rounded-xl shadow-2xl">
            
            <form action="{{ route('it.maintenance.store') }}" method="POST">
                @csrf
                <input type="hidden" name="date" id="modalDateInput">

                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-100 rounded-t">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Tambah Jadwal Baru</h3>
                        <p class="text-sm text-gray-500 mt-1 flex items-center">
                            <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span id="displayDate"></span>
                        </p>
                    </div>
                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-100 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors" data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                
                <div class="p-4 md:p-5 space-y-5">
                    <div>
                        <label class="block mb-3 text-sm font-medium text-gray-900">Jenis Kegiatan</label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            
                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="appointment" class="peer sr-only" checked onchange="toggleAssetField()">
                                <div class="rounded-lg border border-gray-200 bg-white p-3 hover:bg-gray-50 peer-checked:border-blue-600 peer-checked:ring-1 peer-checked:ring-blue-600 transition-all text-center">
                                    <div class="text-blue-600 mb-1 flex justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">Catatan</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="meeting" class="peer sr-only" onchange="toggleAssetField()">
                                <div class="rounded-lg border border-gray-200 bg-white p-3 hover:bg-gray-50 peer-checked:border-emerald-600 peer-checked:ring-1 peer-checked:ring-emerald-600 transition-all text-center">
                                    <div class="text-emerald-600 mb-1 flex justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">Meeting</div>
                                </div>
                            </label>

                            <label class="cursor-pointer">
                                <input type="radio" name="type" value="maintenance" class="peer sr-only" onchange="toggleAssetField()">
                                <div class="rounded-lg border border-gray-200 bg-white p-3 hover:bg-gray-50 peer-checked:border-red-600 peer-checked:ring-1 peer-checked:ring-red-600 transition-all text-center">
                                    <div class="text-red-600 mb-1 flex justify-center">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                    </div>
                                    <div class="text-sm font-medium text-gray-900">Maintenance</div>
                                </div>
                            </label>

                        </div>
                    </div>
                    <div id="titleField">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Judul Kegiatan</label>
                        <input type="text" name="title" id="titleInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" placeholder="Contoh: Diskusi Anggaran IT" required>
                    </div>
                    <div id="assetField" class="hidden">
                        <label class="block mb-2 text-sm font-medium text-gray-900">Pilih Aset untuk Maintenance</label>
                        <select name="asset_id" id="assetSelect" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5">
                            <option value="">-- Pilih Aset --</option>
                            @foreach($assets as $asset)
                                <option value="{{ $asset->id }}" data-name="{{ $asset->name }}">{{ $asset->name }} ({{ $asset->code }})</option>
                            @endforeach
                        </select>
                        <p class="mt-2 text-xs text-gray-500 flex items-center">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Judul akan otomatis diisi dengan nama aset.
                        </p>
                    </div>

                </div>
                
                <div class="flex items-center p-4 md:p-5 border-t border-gray-100 rounded-b bg-gray-50">
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">Simpan Jadwal</button>
                    <button data-modal-hide="default-modal" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 transition-colors">Batal</button>
                </div>
            </form>

        </div>
    </div>
</div>
<script>
    function openModalWithDate(fullDate, formattedDate) {
        document.getElementById('modalDateInput').value = fullDate;
        document.getElementById('displayDate').innerText = formattedDate;
        document.querySelector('input[name="type"][value="appointment"]').checked = true;
        document.getElementById('titleInput').value = '';
        document.getElementById('assetSelect').value = '';
        toggleAssetField(); 
    }

    function toggleAssetField() {
        const type = document.querySelector('input[name="type"]:checked').value;
        const assetField = document.getElementById('assetField');
        const titleField = document.getElementById('titleField');
        const titleInput = document.getElementById('titleInput');
        const assetSelect = document.getElementById('assetSelect');

        if (type === 'maintenance') {
            assetField.classList.remove('hidden');
            titleField.classList.add('hidden'); 
            titleInput.removeAttribute('required'); 
            assetSelect.setAttribute('required', 'required'); 
        } else {
            assetField.classList.add('hidden');
            titleField.classList.remove('hidden'); 
            titleInput.setAttribute('required', 'required'); 
            assetSelect.removeAttribute('required');
            assetSelect.value = ""; 
        }
    }
    document.getElementById('assetSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const assetName = selectedOption.getAttribute('data-name');
        if (assetName) {
            document.getElementById('titleInput').value = "Maintenance: " + assetName;
        }
    });
</script>

@endsection