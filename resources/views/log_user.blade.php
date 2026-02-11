@extends('layouts.layout')

@section('title', 'Log User')

@section('content')

<div class="max-w-[85rem] px-4 py-10 sm:px-6 lg:px-8 lg:py-14 mx-auto">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Pengguna</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola akses karyawan, teknisi, dan administrator.</p>
        </div>
        <button data-modal-target="add-user-modal" data-modal-toggle="add-user-modal" class="text-white bg-blue-700 hover:bg-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 flex items-center shadow-sm transition-all">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah User Baru
        </button>
    </div>

    @if(session('success'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200 shadow-sm flex items-center" role="alert">
            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20"><path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/></svg>
            <div><span class="font-medium">Berhasil!</span> {{ session('success') }}</div>
        </div>
    @endif

    <div class="mb-6 border-b border-gray-200">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-tab" data-tabs-toggle="#default-tab-content" role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-colors" id="user-tab" data-tabs-target="#user" type="button" role="tab" aria-controls="user" aria-selected="false">Karyawan (User)</button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-colors" id="it-tab" data-tabs-target="#it" type="button" role="tab" aria-controls="it" aria-selected="false">IT Support</button>
            </li>
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 transition-colors" id="admin-tab" data-tabs-target="#admin" type="button" role="tab" aria-controls="admin" aria-selected="false">Administrator</button>
            </li>
        </ul>
    </div>

    <div id="default-tab-content">
        
        {{-- 1. TABEL USER --}}
        <div class="hidden" id="user" role="tabpanel" aria-labelledby="user-tab">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Nama Lengkap</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Username</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Email / Divisi</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-left">Status</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users->where('role', 'user') as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $user->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $user->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-gray-900 font-medium">{{ $user->email }}</div>
                                    <div class="text-xs">{{ $user->division }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button type="button" data-modal-target="edit-modal-{{ $user->id }}" data-modal-toggle="edit-modal-{{ $user->id }}" class="text-blue-600 hover:text-blue-900 hover:underline">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">Tidak ada data karyawan ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 2. TABEL IT SUPPORT --}}
        <div class="hidden" id="it" role="tabpanel" aria-labelledby="it-tab">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-blue-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-blue-800 uppercase tracking-wider text-left">Nama Teknisi</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-blue-800 uppercase tracking-wider text-left">Username</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-blue-800 uppercase tracking-wider text-left">Kontak & Divisi</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-blue-800 uppercase tracking-wider text-left">Beban Kerja</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-blue-800 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users->where('role', 'it_support') as $it)
                            <tr class="hover:bg-blue-50/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $it->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $it->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-gray-900 font-medium">{{ $it->email }}</div>
                                    <div class="text-xs">{{ $it->division }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 border border-yellow-200">
                                        {{ $it->it_tickets_count ?? 0 }} Tiket Aktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button type="button" data-modal-target="edit-modal-{{ $it->id }}" data-modal-toggle="edit-modal-{{ $it->id }}" class="text-blue-600 hover:text-blue-900 hover:underline">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">Tidak ada data IT Support ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- 3. TABEL ADMIN --}}
        <div class="hidden" id="admin" role="tabpanel" aria-labelledby="admin-tab">
            <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-purple-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-purple-800 uppercase tracking-wider text-left">Nama Admin</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-purple-800 uppercase tracking-wider text-left">Username</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-purple-800 uppercase tracking-wider text-left">Email / Divisi</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-purple-800 uppercase tracking-wider text-left">Role</th>
                                <th scope="col" class="px-6 py-4 text-xs font-semibold text-purple-800 uppercase tracking-wider text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @forelse($users->where('role', 'admin') as $admin)
                            <tr class="hover:bg-purple-50/30 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $admin->full_name }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">{{ $admin->username }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <div class="text-gray-900 font-medium">{{ $admin->email }}</div>
                                    <div class="text-xs">{{ $admin->division }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200">
                                        Administrator
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <button type="button" data-modal-target="edit-modal-{{ $admin->id }}" data-modal-toggle="edit-modal-{{ $admin->id }}" class="text-blue-600 hover:text-blue-900 hover:underline">Edit</button>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500 italic bg-gray-50">Tidak ada data Administrator ditemukan.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- MODAL TAMBAH USER --}}
<div id="add-user-modal" data-modal-backdrop="static" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 rounded-t bg-gray-50/50">
                <div>
                    <h3 class="text-xl font-bold text-gray-900">
                        Tambah Pengguna Baru
                    </h3>
                    <p class="text-xs text-gray-500 mt-1">Daftarkan akun karyawan atau teknisi ke dalam sistem</p>
                </div>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center transition-colors" data-modal-hide="add-user-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>

            <div class="p-6">
                <form action="{{ route('admin.storeUser') }}" method="POST">
                    @csrf
                    @if ($errors->any())
                        <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50 border border-red-200">
                            <ul class="list-disc ps-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Username</label>
                            <input type="text" name="username" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all placeholder:text-gray-400" placeholder="johndoe" required />
                        </div>

                        <div class="col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                            <input type="text" name="full_name" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all placeholder:text-gray-400" placeholder="John Doe" required />
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Email Perusahaan</label>
                            <input type="email" name="email" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all placeholder:text-gray-400" placeholder="name@company.com" required />
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Divisi</label>
                            <input type="text" name="division" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all placeholder:text-gray-400" placeholder="Contoh: Marketing / IT" required />
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Role Akses</label>
                            <select name="role" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all">
                                <option value="user">User (Karyawan)</option>
                                <option value="it_support">IT Support (Teknisi)</option>
                                <option value="admin">Administrator</option>
                            </select>
                        </div>

                        <div class="col-span-2 md:col-span-1">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
                            <div class="relative">
                                <input type="password" id="password_input" name="password" 
                                    class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-xl focus:ring-blue-500 focus:border-blue-500 block w-full p-3 transition-all placeholder:text-gray-400" 
                                    placeholder="••••••••" required />
                                
                                <button type="button" id="btn_toggle_pw" class="absolute inset-y-0 end-0 flex items-center pe-3.5 text-gray-400 hover:text-blue-600 transition-colors">
                                    <svg id="eye_open" class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <svg id="eye_closed" class="w-5 h-5 hidden" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8 pt-5 border-t border-gray-100">
                        <button data-modal-hide="add-user-modal" type="button" class="py-3 px-5 text-sm font-medium text-gray-600 focus:outline-none bg-white rounded-xl border border-gray-200 hover:bg-gray-50 hover:text-blue-700 transition-all">
                            Batal
                        </button>
                        <button type="submit" class="py-3 px-8 text-sm font-bold text-center text-white bg-blue-600 rounded-xl hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 shadow-lg shadow-blue-500/30 transition-all">
                            Simpan User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL EDIT USER (LOOPING) --}}
@foreach($users as $user)
<div id="edit-modal-{{ $user->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-2xl max-h-full">
        <div class="relative bg-white rounded-xl shadow-2xl border border-gray-100">
            <div class="flex items-center justify-between p-5 border-b border-gray-100 rounded-t bg-gray-50/50">
                <h3 class="text-xl font-bold text-gray-900">Edit {{ $user->full_name }}</h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center" data-modal-hide="edit-modal-{{ $user->id }}">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                </button>
            </div>
            <form action="{{ route('admin.updateUser', $user->id) }}" method="POST"> 
                @csrf
                @method('PUT')
                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Username</label>
                        <input type="text" name="username" value="{{ $user->username }}" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3" required />
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Nama Lengkap</label>
                        <input type="text" name="full_name" value="{{ $user->full_name }}" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3" required />
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3" required />
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Divisi</label>
                        <input type="text" name="division" value="{{ $user->division }}" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3" required />
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Role</label>
                        <select name="role" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>User</option>
                            <option value="it_support" {{ $user->role == 'it_support' ? 'selected' : '' }}>IT Support</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                    </div>
                    <div class="col-span-1">
                        <label class="block mb-2 text-sm font-semibold text-gray-700">Password Baru</label>
                        <input type="password" name="password" class="bg-gray-50 border border-gray-200 text-sm rounded-xl block w-full p-3" placeholder="Kosongkan jika tetap" />
                    </div>
                </div>
                <div class="flex items-center justify-end gap-3 p-6 border-t border-gray-100">
                    <button data-modal-hide="edit-modal-{{ $user->id }}" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-600 bg-white border border-gray-200 rounded-xl hover:bg-gray-50">Batal</button>
                    <button type="submit" class="py-2.5 px-8 text-sm font-bold text-white bg-blue-600 rounded-xl hover:bg-blue-700 shadow-lg shadow-blue-500/30">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const passwordInput = document.getElementById('password_input');
        const toggleBtn = document.getElementById('btn_toggle_pw');
        const eyeOpen = document.getElementById('eye_open');
        const eyeClosed = document.getElementById('eye_closed');

        if (toggleBtn) {
            toggleBtn.addEventListener('click', function () {
                const isPassword = passwordInput.getAttribute('type') === 'password';
                passwordInput.setAttribute('type', isPassword ? 'text' : 'password');
                eyeOpen.classList.toggle('hidden');
                eyeClosed.classList.toggle('hidden');
            });
        }
    });
</script>

@endsection