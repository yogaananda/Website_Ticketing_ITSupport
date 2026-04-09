@extends('layouts.layout')

@section('title', 'IT Knowledge Base')

@section('content')

<div class="max-w-[85rem] px-4 py-8 sm:px-6 lg:px-8 mx-auto">
    
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Buku Panduan IT (SOP)
            </h1>
            <p class="mt-1 text-sm text-gray-500">
                Panduan standar operasional (SOP) dan langkah-langkah troubleshooting jika terjadi kendala teknis.
            </p>
        </div>
        <div>
            <button data-modal-target="modal-crud" data-modal-toggle="modal-crud" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition-all bg-indigo-600 border border-transparent rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Panduan Baru
            </button>
        </div>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($articles as $article)
        <div class="flex flex-col bg-white border border-gray-200 shadow-sm rounded-xl">
            <div class="p-4 md:p-5">
                <div class="flex justify-between items-start mb-3">
                    <span class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-md text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                        {{ $article->category ?? 'Umum' }}
                    </span>
                    @if(Auth::id() == $article->author_id || Auth::user()->role == 'admin')
                    <form action="{{ route('it.knowledge_base.destroy', $article->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus artikel ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-gray-400 hover:text-red-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    </form>
                    @endif
                </div>
                <h3 class="text-lg font-bold text-gray-800">
                    {{ $article->title }}
                </h3>
                <p class="mt-2 text-sm text-gray-500 line-clamp-3">
                    {{ strip_tags($article->content) }}
                </p>
                <div class="mt-4">
                    <button type="button" data-modal-target="modal-read-{{ $article->id }}" data-modal-toggle="modal-read-{{ $article->id }}" class="inline-flex items-center gap-2 text-sm font-medium text-indigo-600 hover:text-indigo-800">
                        Baca Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            <div class="bg-gray-50 border-t border-gray-100 py-3 px-4 md:px-5 rounded-b-xl flex items-center justify-between">
                <p class="text-xs text-gray-500">
                    Oleh <span class="font-semibold text-gray-700">{{ $article->author->full_name ?? $article->author->username }}</span>
                </p>
                <p class="text-xs text-gray-400">
                    {{ $article->created_at->format('d M Y') }}
                </p>
            </div>
        </div>

        <!-- Modal Read -->
        <div id="modal-read-{{ $article->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-2xl max-h-full">
                <div class="relative bg-white rounded-xl shadow-2xl">
                    <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t">
                        <h3 class="text-xl font-semibold text-gray-900">
                            {{ $article->title }}
                        </h3>
                        <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-read-{{ $article->id }}">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                        </button>
                    </div>
                    <div class="p-4 md:p-5 space-y-4">
                        <div class="prose max-w-none text-sm text-gray-700 whitespace-pre-wrap leading-relaxed">
{{ $article->content }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @empty
        <div class="col-span-full">
            <div class="text-center p-12 bg-white border border-gray-200 rounded-xl shadow-sm">
                <svg class="w-12 h-12 text-gray-300 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900">Belum Ada Panduan</h3>
                <p class="mt-1 text-sm text-gray-500">Mulai dokumentasikan standar operasional dan masalah teknis di perusahaan Anda.</p>
            </div>
        </div>
        @endforelse
    </div>

</div>

<!-- Modal Create -->
<div id="modal-crud" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-3xl max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl">
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">
                    Buat Panduan Baru
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-crud">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="{{ route('it.knowledge_base.store') }}" method="POST">
                @csrf
                <div class="p-4 md:p-5 space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Judul Masalah / Solusi / SOP <span class="text-red-500">*</span></label>
                            <input type="text" name="title" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5" placeholder="Contoh: SOP Troubleshooting Jaringan WiFi">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-900">Kategori Panduan <span class="text-red-500">*</span></label>
                            <select name="category" required class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-600 focus:border-indigo-600 block w-full p-2.5">
                                <option value="SOP IT">SOP IT</option>
                                <option value="Hardware">Hardware</option>
                                <option value="Software">Software</option>
                                <option value="Jaringan">Jaringan</option>
                                <option value="Umum">Umum / Lainnya</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-900">Langkah Penyelesaian / Standar Prosedur <span class="text-red-500">*</span></label>
                        <textarea name="content" rows="10" required class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-indigo-600 focus:border-indigo-600" placeholder="Jelaskan langkah-langkah secara detail disini..."></textarea>
                    </div>
                </div>
                <div class="flex items-center p-4 md:p-5 border-t border-gray-200 rounded-b">
                    <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center">Simpan Panduan</button>
                    <button data-modal-hide="modal-crud" type="button" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
