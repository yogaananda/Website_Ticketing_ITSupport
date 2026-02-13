<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
    @vite('resources/css/app.css')
  </head>
  <body class="bg-indigo-100 min-h-screen">
    <button data-drawer-target="default-sidebar" data-drawer-toggle="default-sidebar" aria-controls="default-sidebar" type="button" class="text-heading bg-transparent box-border border border-transparent hover:bg-neutral-secondary-medium focus:ring-4 focus:ring-neutral-tertiary font-medium leading-5 rounded-base ms-3 mt-3 text-sm p-2 focus:outline-none inline-flex sm:hidden">
        <span class="sr-only">Open sidebar</span>
        <svg class="w-6 h-6" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h10"/>
        </svg>
    </button>

    <aside id="default-sidebar" class="fixed top-0 left-0 z-40 w-64 h-full transition-transform -translate-x-full sm:translate-x-0" aria-label="Sidebar">
    <div class="h-full px-5 py-6 overflow-y-auto bg-indigo-200 border-e border-default">

        {{-- LOGO / BRAND --}}
        <div class="flex items-center ps-2.5 mb-8">
            <svg class="w-8 h-8 me-3 text-indigo-700" fill="currentColor" viewBox="0 0 20 20"><path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z"></path><path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z"></path></svg>
            <span class="self-center text-xl font-bold whitespace-nowrap text-indigo-900 uppercase tracking-wider">Tiket Barang</span>
        </div>

        {{-- LOGIKA DASHBOARD & NOTIFIKASI --}}
        @php
            $role = auth()->user()->role;
            $dashboardRoute = '#';
            
            // Tentukan Route Dashboard
            if($role === 'admin') $dashboardRoute = route('admin.dashboard'); 
            elseif($role === 'it_support') $dashboardRoute = route('it.dashboard');
            elseif($role === 'user') $dashboardRoute = route('user.dashboard');

            // --- HITUNG NOTIFIKASI (BADGES) ---
            $pendingApprovals = 0;
            $openTickets = 0;

            if ($role === 'admin') {
                // Admin butuh tau ada berapa request pending (Aset + Consumable)
                $pendingAssets = \App\Models\AssetLoan::where('status', 'pending')->count();
                $pendingConsumables = \App\Models\ConsumableRequest::where('status', 'pending')->count();
                $pendingApprovals = $pendingAssets + $pendingConsumables;
            }

            if ($role === 'it_support' || $role === 'admin') {
                // IT Support butuh tau ada berapa tiket status 'open'
                // Asumsi model Ticket ada & punya status 'open'
                if(class_exists('\App\Models\Ticket')){
                    $openTickets = \App\Models\Ticket::where('status', 'open')->count();
                }
            }

            // Helper active state
            function isActive($routes) {
                if(is_array($routes)) {
                    foreach($routes as $r) {
                        if(request()->routeIs($r)) return true;
                    }
                    return false;
                }
                return request()->routeIs($routes);
            }
        @endphp

        <ul class="space-y-3 font-medium">
            
            {{-- 1. DASHBOARD UTAMA --}}
            <li>
                <a href="{{ $dashboardRoute }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive(['admin.dashboard', 'it.dashboard', 'user.dashboard']) ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive(['admin.dashboard', 'it.dashboard', 'user.dashboard']) ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6.025A7.5 7.5 0 1 0 17.975 14H10V6.025Z"/><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 3c-.169 0-.334.01-.5.02V11h7.975c.01-.17.025-.33.025-.5A7.5 7.5 0 0 0 13.5 3Z"/></svg>
                    <span class="ms-3">Dashboard</span>
                </a>
            </li>

            {{-- 2. MENU KHUSUS ADMIN --}}
            @if($role === 'admin')
            <li class="pt-2 mt-2 border-t border-indigo-300/50">
                <p class="text-xs font-semibold text-indigo-500 uppercase px-3 mb-2">Master Data</p>
            </li>
            
            <li> 
                <a href="{{ route('admin.assets.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('admin.assets.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('admin.assets.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    <span class="ms-3">Aset IT (PC/Laptop)</span>
                </a>
            </li>
            <li> 
                <a href="{{ route('admin.consumables.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('admin.consumables.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('admin.consumables.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                    <span class="ms-3">Logistik (Stok)</span>
                </a>
            </li>
            
            <li class="pt-2 mt-2 border-t border-indigo-300/50">
                <p class="text-xs font-semibold text-indigo-500 uppercase px-3 mb-2">Administrasi</p>
            </li>
            <li> 
                <a href="{{ route('admin.log_user') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('admin.log_user') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('admin.log_user') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 1 1 0 5.292M15 21H3v-1a6 6 0 0 1 12 0v1Zm0 0h6v-1a6 6 0 0 0-9-5.197M13 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0Z"/></svg>
                    <span class="ms-3">Manajemen User</span>
                </a>
            </li>
            <li> {{-- APPROVAL DENGAN BADGE --}}
                <a href="{{ route('admin.approvals.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('admin.approvals.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('admin.approvals.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">Persetujuan</span>
                </a>
            </li>
            <li> 
                <a href="{{ route('admin.report') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('admin.report') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('admin.report') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    <span class="ms-3">Laporan Lengkap</span>
                </a>
            </li>
            @endif

            {{-- 3. MENU KHUSUS IT SUPPORT --}}
            @if($role === 'it_support')
            <li class="pt-2 mt-2 border-t border-indigo-300/50">
                <p class="text-xs font-semibold text-indigo-500 uppercase px-3 mb-2">Operasional</p>
            </li>
            <li> 
                <a href="#" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('maintenance.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('maintenance.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                    <span class="ms-3">Jadwal Maintenance</span>
                </a>
            </li>
            <li> 
                <a href="{{ route('it.approvals.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('it.approvals.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('it.approvals.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" /></svg>
                    <span class="ms-3">Peminjaman Aset</span>
                </a>
            </li>
            <li> 
                <a href="{{ route('it.procurements.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('it.procurements.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('procurements.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    <span class="ms-3">Permintaan Barang</span>
                </a>
            </li>
            @endif

            {{-- 4. MENU KHUSUS USER --}}
            @if($role === 'user')
            <li class="pt-2 mt-2 border-t border-indigo-300/50">
                <p class="text-xs font-semibold text-indigo-500 uppercase px-3 mb-2">Layanan</p>
            </li>
            <li> 
                <a href="{{ route('user.assets.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('user.assets.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('user.assets.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span class="ms-3">Pinjam Aset</span>
                </a>
            </li>
            <li> 
                <a href="{{ route('user.consumables.index') }}" class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('user.consumables.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('user.consumables.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" /></svg>
                    <span class="ms-3">Minta Tinta/ATK</span>
                </a>
            </li>
            @endif

            {{-- 5. MENU UMUM (Tiket Kerusakan + BADGE) --}}
            <li class="pt-2 mt-2 border-t border-indigo-300/50">
                <p class="text-xs font-semibold text-indigo-500 uppercase px-3 mb-2">Helpdesk</p>
            </li>
            <li>
                <a href="{{ route('tickets.index') }}" 
                   class="flex items-center px-3 py-2 rounded-lg transition-all group {{ isActive('tickets.*') ? 'bg-white text-indigo-700 shadow-sm' : 'text-gray-700 hover:bg-white/50 hover:text-indigo-700' }}">
                    <svg class="w-5 h-5 transition duration-75 {{ isActive('tickets.*') ? 'text-indigo-700' : 'group-hover:text-indigo-700' }}" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 13h3.439a.991.991 0 0 1 .908.6 3.978 3.978 0 0 0 7.306 0 .99.99 0 0 1 .908-.6H20M4 13v6a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-6M4 13l2-9h12l2 9M9 7h6m-7 3h8"/></svg>
                    <span class="ms-3 flex-1 whitespace-nowrap">Tiket Kerusakan</span>
                    
                    {{-- BADGE BIRU (Untuk IT Support/Admin jika ada tiket open) --}}
                    @if(($role === 'it_support' || $role === 'admin') && $openTickets > 0)
                        <span class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                            {{ $openTickets }}
                        </span>
                    @endif
                </a>
            </li>

            <li class="pt-4 mt-4 border-t border-indigo-300">
                <a href="javascript:void(0)" data-modal-target="modal-logout" data-modal-toggle="modal-logout" 
                   class="flex items-center px-3 py-2 text-gray-700 rounded-lg hover:bg-white/50 hover:text-indigo-700 group transition-all cursor-pointer">
                    <svg class="w-5 h-5 transition duration-75 group-hover:text-indigo-700" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M16 12H4m12 0-4 4m4-4-4-4m3-4h2a3 3 0 0 1 3 3v10a3 3 0 0 1-3 3h-2"/></svg>
                    <span class="ms-3">Keluar</span>
                </a>
            </li>
        </ul>
    </div>
</aside>

    <div id="modal-logout" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white border border-gray-200 rounded-lg shadow-lg p-4 md:p-6">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center" data-modal-hide="modal-logout">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500">Apakah Anda yakin ingin keluar dari sistem?</h3>
                    <div class="flex items-center justify-center space-x-4">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" data-modal-hide="modal-logout" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya, Keluar
                            </button>
                        </form>
                        <button data-modal-hide="modal-logout" type="button" class="py-2.5 px-5 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 sm:ml-64">
        <div class="p-4 border-1 border-default border-dashed rounded-base bg-light">
            @yield('content')
        </div>
    </div>
  </body>
  <script src="{{ asset('js/main.js') }}"></script>
  <script src="https://cdn.jsdelivr.net/npm/flowbite@4.0.1/dist/flowbite.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.46.0/dist/apexcharts.min.js"></script>
</html>