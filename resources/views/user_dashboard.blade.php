@extends('layouts.layout')

@section('title', 'Halaman Beranda')

@section('content')
   
<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
  <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
    <div>
      <h1 class="text-2xl font-bold text-gray-800 dark:text-dark-neutral-200">
        Halo, {{ Auth::user()->full_name ?? Auth::user()->username }}
      </h1>
      <p class="mt-1 text-gray-600 dark:text-neutral-400">
        Selamat datang di IT Support Center. Ada yang bisa kami bantu?
      </p>
    </div>

      <button 
         type="button"
         data-modal-target="static-modal" 
         data-modal-toggle="static-modal" 
         class="py-3 px-4 inline-flex items-center gap-x-2 text-sm font-semibold rounded-lg border border-transparent bg-indigo-600 text-white hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-300 shadow-sm disabled:opacity-50 disabled:pointer-events-none">
         <svg class="flex-shrink-0 size-4" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M5 12h14"/>
            <path d="M12 5v14"/>
         </svg>
         Buat Tiket Baru
      </button>
  </div>
  <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6 mb-10">
      <a href="#" class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
         <p class="text-body">Tiket Sedang Proses</p>
         <div class="mt-1 flex items-center gap-x-2">
          <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
            {{ $activeTickets }} Tiket
          </h3>
          @if($activeTickets > 0)
          <span class="flex items-center gap-x-1 text-green-600">
            <span class="inline-block text-xs bg-green-100 text-green-800 py-1 px-2 rounded-full">Aktif</span>
          </span>
          @endif
        </div> 
      </a>

      <a href="#" class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
         <p class="text-body">Posisi Antrean Saya</p>
         <div class="mt-1 flex items-center gap-x-2">
          <h3 class="text-xl sm:text-2xl font-bold text-red-600 dark:text-red-500">
            {{ $globalQueue }} Tiket
          </h3>
          <span class="text-xs text-gray-500">Menunggu Penanganan</span>
        </div> 
      </a>

      <a href="#" class="bg-neutral-primary-soft block max-w-sm p-6 border border-default rounded-base shadow-xs hover:bg-neutral-secondary-medium">
         <p class="text-body">Total Masalah Selesai</p>
         <div class="mt-1 flex items-center gap-x-2">
          <h3 class="text-xl sm:text-2xl font-medium text-gray-800">
            {{ $resolvedTickets }}
          </h3>
          <span class="text-xs text-gray-500">Terima Kasih!</span>
        </div> 
      </a>
   </div>
   
<div class="relative overflow-x-auto bg-white shadow-sm rounded-lg border border-gray-200">
      <table class="w-full text-sm text-left rtl:text-right text-gray-500">
         <thead class="text-sm text-gray-700 bg-gray-50 border-b border-gray-200">
            <tr>
               <th scope="col" class="px-6 py-3 font-medium">No. Tiket</th>
               <th scope="col" class="px-6 py-3 font-medium">Judul / Kategori</th>
               <th scope="col" class="px-6 py-3 font-medium">Tgl Lapor</th>
               <th scope="col" class="px-6 py-3 font-medium">Urgensi</th>
               <th scope="col" class="px-6 py-3 font-medium">Status</th>
               <th scope="col" class="px-6 py-3 font-medium text-center">Aksi</th>
            </tr>
         </thead>
         <tbody>
            @forelse($tickets as $ticket)
            <tr class="bg-white border-b border-gray-200 hover:bg-gray-50">
                <td class="px-6 py-4 font-bold text-indigo-600">
                    {{ $ticket->ticket_code }}
                </td>
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap">
                    {{ Str::limit($ticket->title, 30) }}
                    <br>
                    <span class="text-xs font-normal text-gray-500">{{ $ticket->category->name ?? 'Umum' }}</span>
                </th>
                <td class="px-6 py-4">
                    {{ $ticket->created_at->format('d M Y') }}
                </td>
                <td class="px-6 py-4">
                    @if($ticket->priority == 'high' || $ticket->priority == 'critical')
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-red-100 text-red-800 border border-red-200 shadow-sm">
                            <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM8 4a.905.905 0 0 0-.9.995l.35 3.507a.552.552 0 0 0 1.1 0l.35-3.507A.905.905 0 0 0 8 4zm.002 6a1 1 0 1 0 0 2 1 1 0 0 0 0-2z"/></svg>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    @elseif($ticket->priority == 'medium')
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200 shadow-sm">
                            <span class="size-1.5 inline-block rounded-full bg-yellow-800"></span>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    @else
                        <span class="inline-flex items-center gap-x-1.5 py-1.5 px-3 rounded-full text-xs font-medium bg-green-100 text-green-800 border border-green-200 shadow-sm">
                            <svg class="size-2.5 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16"><path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/></svg>
                            {{ ucfirst($ticket->priority) }}
                        </span>
                    @endif
                </td>
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
                <td class="px-6 py-4 text-center">
                    <button type="button" 
                            data-modal-target="modal-detail-{{ $ticket->id }}" 
                            data-modal-toggle="modal-detail-{{ $ticket->id }}" 
                            class="font-medium text-blue-600 hover:underline">
                        Detail
                    </button>
                    <div id="modal-detail-{{ $ticket->id }}" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full text-left">
                        <div class="relative p-4 w-full max-w-2xl max-h-full">
                            <div class="relative bg-white border border-gray-200 rounded-lg shadow-lg">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b border-gray-200 rounded-t">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Detail Laporan Barang
                                    </h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-detail-{{ $ticket->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                </div>
                                <div class="p-4 md:p-6 space-y-6">
                                    <div class="flex justify-between items-start border-b border-dashed border-gray-200 pb-4">
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900">#{{ $ticket->ticket_code }} - {{ $ticket->title }}</h3>
                                            <p class="text-sm text-gray-500">Dilaporkan pada: {{ $ticket->created_at->format('d M Y') }}</p>
                                        </div>
                                        
                                        @if($ticket->status == 'open')
                                            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full border border-red-300">Open</span>
                                        @elseif($ticket->status == 'in_progress')
                                            <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full border border-yellow-300">Proses</span>
                                        @else
                                            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full border border-green-300">Selesai</span>
                                        @endif
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <div>
                                            <h4 class="font-semibold text-gray-900 mb-2 text-sm uppercase tracking-wider">Deskripsi Kerusakan:</h4>
                                            <p class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg border border-gray-100">
                                                {{ $ticket->description }}
                                            </p>
                                            
                                            <h4 class="font-semibold text-gray-900 mt-6 mb-2 text-sm uppercase tracking-wider">Foto Bukti:</h4>
                                            @if($ticket->image)
                                                <img src="{{ asset('storage/' . $ticket->image) }}" class="rounded-lg border border-gray-200 w-full object-cover h-44 shadow-sm">
                                            @else
                                                <div class="w-full h-44 bg-gray-50 rounded-lg border border-gray-200 flex items-center justify-center text-gray-400 text-sm italic">
                                                    Tidak ada foto bukti
                                                </div>
                                            @endif
                                        </div>
                                        <div class="md:border-s md:border-gray-100 md:ps-6">
                                            <h4 class="font-semibold text-gray-900 mb-4 text-sm uppercase tracking-wider">Log Aktivitas:</h4>
                                            <ol class="relative border-s border-gray-200 ml-2">                  
                                                <li class="mb-6 ms-4">
                                                    <div class="absolute w-3 h-3 bg-gray-400 rounded-full mt-1.5 -start-1.5 border border-white"></div>
                                                    <time class="mb-1 text-xs font-normal leading-none text-gray-400">{{ $ticket->created_at->format('d M, H:i') }}</time>
                                                    <h3 class="text-sm font-semibold text-gray-900">Laporan Diterima</h3>
                                                    <p class="text-xs text-gray-500">Tiket masuk ke sistem.</p>
                                                </li>
                                                @foreach($ticket->comments ?? [] as $comment)
                                                <li class="mb-6 ms-4">
                                                    @php $dotColor = str_contains(strtoupper($comment->message), 'SELESAI') ? 'bg-green-500' : 'bg-blue-500'; @endphp
                                                    <div class="absolute w-3 h-3 {{ $dotColor }} rounded-full mt-1.5 -start-1.5 border border-white"></div>
                                                    <time class="mb-1 text-xs font-normal leading-none text-gray-400">{{ $comment->created_at->format('d M, H:i') }}</time>
                                                    <h3 class="text-sm font-semibold text-gray-900">{{ $comment->user->full_name ?? 'Teknisi' }}</h3>
                                                    <div class="text-xs text-gray-600 bg-gray-50 p-2 rounded mt-1 border border-gray-100">{{ $comment->message }}</div>
                                                </li>
                                                @endforeach
                                            </ol>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                                    <button data-modal-hide="modal-detail-{{ $ticket->id }}" type="button" class="ms-auto text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-colors">
                                        Tutup Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-10 text-center text-gray-500">
                    Belum ada tiket laporan yang dibuat.
                </td>
            </tr>
            @endforelse
        </tbody>
      </table>
      
      <div class="bg-white px-6 py-4 border-t border-gray-100 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="text-sm font-normal text-gray-500">
                Showing 
                <span class="font-semibold text-gray-900">{{ $tickets->firstItem() ?? 0 }}</span> 
                to 
                <span class="font-semibold text-gray-900">{{ $tickets->lastItem() ?? 0 }}</span> 
                of 
                <span class="font-semibold text-gray-900">{{ $tickets->total() }}</span> 
                results
            </div>
            @if ($tickets->hasPages())
            <nav aria-label="Page navigation example">
                <ul class="flex -space-x-px text-sm">
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
<!------------------------------------------------------------------------------ component ------------------------------------------------------------------------------------------------------------------------------->
<div id="static-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white border border-gray-200 rounded-xl shadow-sm p-4 md:p-6">
            
            <div class="flex items-center justify-between border-b border-gray-200 pb-4 md:pb-5 mb-4">
                <h3 class="text-lg font-bold text-gray-800">
                    Input Laporan Masalah
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="static-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                @csrf <div class="mb-4">
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Judul Masalah</label>
                    <input type="text" name="title" id="title" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5" placeholder="Contoh: AC Bocor di Ruang Meeting" required>
                </div>

                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-900">Kategori</label>
                        <select name="category_id" id="category" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="1">Hardware</option>
                            <option value="2">Software</option>
                            <option value="3">Jaringan</option>
                        </select>
                    </div>

                    <div class="col-span-2 sm:col-span-1">
                        <label for="priority" class="block mb-2 text-sm font-medium text-gray-900">Urgensi</label>
                        <select name="priority" id="priority" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                            <option value="low">Rendah (Low)</option>
                            <option value="medium">Sedang (Medium)</option>
                            <option value="high">Tinggi (High)</option>
                        </select>
                    </div>
                </div>
                
                <div class="mb-4">
                    <label for="description" class="block mb-2 text-sm font-medium text-gray-900">Deskripsi Detail</label>
                    <textarea name="description" id="description" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500" placeholder="Jelaskan kronologi kerusakannya..." required></textarea>
                </div>

                <div class="mb-4">
                    <label class="block mb-2 text-sm font-medium text-gray-900">Foto Bukti (Opsional)</label>
                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none" name="image" type="file">
                    <p class="mt-1 text-xs text-gray-500">SVG, PNG, JPG or GIF (MAX. 2MB).</p>
                </div>

                <div class="flex items-center justify-end pt-4 border-t border-gray-200">
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">
                        Kirim Laporan
                    </button>
                    <button data-modal-hide="static-modal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection