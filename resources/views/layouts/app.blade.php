<!DOCTYPE html>
<html lang="en" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BukuKita - Aplikasi Toko Buku')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" crossorigin>
    <link rel="stylesheet" href="https://fonts.bunny.net/css?family=Figtree:400,600,700,800,900&display=swap">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">

    <!-- Tailwind via Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        [x-cloak] {
            display: none !important
        }

        /* Tooltip Style */
        .tooltip {
            position: relative;
        }

        .tooltip-text {
            visibility: hidden;
            opacity: 0;
            position: fixed;
            left: 85px;
            background-color: #1f2937;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            white-space: nowrap;
            font-size: 14px;
            z-index: 9999;
            transition: opacity 0.2s, visibility 0.2s;
            pointer-events: none;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .tooltip-text::before {
            content: '';
            position: absolute;
            right: 100%;
            top: 50%;
            transform: translateY(-50%);
            border: 6px solid transparent;
            border-right-color: #1f2937;
        }

        .tooltip:hover .tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        /* Hide tooltip on mobile */
        @media (max-width: 768px) {
            .tooltip-text {
                display: none !important;
            }
        }
    </style>
</head>
@stack('scripts')

<body class="bg-[#F9FAFB] h-screen overflow-x-hidden font-sans flex" x-data="{
    sidebarOpen: window.innerWidth >= 768 ? (JSON.parse(localStorage.getItem('sidebarOpen')) ?? true) : false,
    mobileMenuOpen: false,
    toggleSidebar() {
        if (window.innerWidth >= 768) {
            this.sidebarOpen = !this.sidebarOpen;
            localStorage.setItem('sidebarOpen', JSON.stringify(this.sidebarOpen));
        } else {
            this.mobileMenuOpen = !this.mobileMenuOpen;
        }
    },
    closeMobileMenu() {
        this.mobileMenuOpen = false;
    }
}" x-init="$watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', JSON.stringify(val)));
window.addEventListener('resize', () => {
    if (window.innerWidth >= 768) {
        mobileMenuOpen = false;
        sidebarOpen = JSON.parse(localStorage.getItem('sidebarOpen')) ?? true;
    } else {
        sidebarOpen = false;
    }
});">

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen" x-transition:enter="transition-opacity ease-linear duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click="closeMobileMenu()"
        class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden" x-cloak>
    </div>

    <!-- Sidebar -->
    <aside
        :class="{
            'w-64': sidebarOpen && window.innerWidth >= 768,
            'w-20': !sidebarOpen && window.innerWidth >= 768,
            'translate-x-0': mobileMenuOpen || window.innerWidth >= 768,
            '-translate-x-full': !mobileMenuOpen && window.innerWidth < 768
        }"
        class="fixed left-0 top-0 h-full bg-white text-gray-800 flex flex-col shadow-lg border-r border-gray-200 transition-all duration-300 z-40 md:translate-x-0">

        <!-- Header -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <img src="{{ asset('images/logo_doang.png') }}" alt="Logo BukuKita" class="h-10 w-auto object-contain">
            <span x-show="sidebarOpen || mobileMenuOpen" x-transition
                class="ml-2 font-bold text-xl tracking-wide text-blue-500 font-sans italic">BukuKita</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm overflow-y-auto">
            {{-- Menu Admin --}}
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-tachometer-alt text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Dashboard Admin</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Dashboard Admin</span>
                </a>
                <a href="{{ route('admin.buku.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.buku.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Management Buku</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Management Buku</span>
                </a>
                <a href="{{ route('admin.stok_harga.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.stok_harga.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-dollar-sign text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Stok & Harga</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Stok & Harga</span>
                </a>
                <a href="{{ route('admin.kategori.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.kategori.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-tags text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Management Kategori</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Management Kategori</span>
                </a>
                <a href="{{ route('admin.kasir.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.kasir.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-users text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Management Kasir</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Management Kasir</span>
                </a>
                <a href="{{ route('admin.riwayat_transaksi.index') }}"
                    @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.riwayat_transaksi.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-wallet text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Riwayat Transaksi</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Riwayat Transaksi</span>
                </a>
                <a href="{{ route('admin.logs.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('admin.logs.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-clipboard-list text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Log Aktivitas</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Log Aktivitas</span>
                </a>
            @endif

            {{-- Menu Kasir --}}
            @if (Auth::check() && Auth::user()->role === 'kasir')
                <a href="{{ route('kasir.dashboard') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('kasir.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-cash-register text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Dashboard Kasir</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Dashboard Kasir</span>
                </a>
                <a href="{{ route('kasir.buku.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('kasir.buku.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Transaksi</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Transaksi</span>
                </a>
                <a href="{{ route('kasir.riwayat_transaksi.index') }}"
                    @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('kasir.riwayat_transaksi.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-wallet text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Riwayat Transaksi</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Riwayat Transaksi</span>
                </a>
                <a href="{{ route('kasir.logs.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('kasir.logs.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-clipboard-list text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Log Aktivitas</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Log Aktivitas</span>
                </a>
            @endif

            {{-- Menu Owner --}}
            @if (Auth::check() && Auth::user()->role === 'owner')
                <a href="{{ route('owner.dashboard') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-user-tie text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Dashboard Owner</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Dashboard Owner</span>
                </a>
                <a href="{{ route('owner.buku.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.buku.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Data Buku</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Data Buku</span>
                </a>
                <a href="{{ route('owner.pegawai.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.pegawai.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-users text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Data Pegawai</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Data Pegawai</span>
                </a>
                <a href="{{ route('owner.laporan.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.laporan.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-chart-bar text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Laporan Penjualan</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Laporan Penjualan</span>
                </a>
                <a href="{{ route('owner.logs.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
                    class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.logs.*') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-clipboard-list text-lg w-6 text-center"></i>
                    <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Log Aktivitas</span>
                    <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Log Aktivitas</span>
                </a>
            @endif
        </nav>

        <!-- Logout -->
        @if (Auth::check())
            <div class="px-4 pb-6">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="button" onclick="confirmLogout()"
                        class="tooltip w-full flex items-center justify-center gap-2 px-4 py-2 rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition cursor-pointer">
                        <i class="fas fa-sign-out-alt text-lg w-6 text-center"></i>
                        <span x-show="sidebarOpen || mobileMenuOpen" x-transition>Logout</span>
                        <span x-show="!sidebarOpen && !mobileMenuOpen" class="tooltip-text">Logout</span>
                    </button>
                </form>
            </div>
        @endif
    </aside>

    <!-- Main Content -->
    <main :class="sidebarOpen && window.innerWidth >= 768 ? 'md:ml-64' : 'md:ml-20'"
        class="flex-1 flex flex-col h-screen transition-all duration-300 ml-0">
        <header
            class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 shadow-sm">
            <button @click="toggleSidebar()" class="cursor-pointer text-gray-600 hover:text-indigo-600">
                <i class="fas fa-bars text-xl"></i>
            </button>

            <!-- Desktop: Waktu -->
            <div class="hidden md:block text-lg font-semibold text-gray-700" id="datetime"></div>

            <!-- User Info -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Notifikasi Real-time -->
                <div class="relative" x-data="{
                    open: false,
                    count: 0,
                    notifications: [],
                    loading: false,
                    async loadNotifications() {
                        this.loading = true;
                        try {
                            const response = await fetch('/notifications', {
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                }
                            });
                            if (response.ok) {
                                const data = await response.json();
                                this.count = data.count;
                                this.notifications = data.notifications;
                            } else {
                                console.error('Error response:', response.status);
                            }
                        } catch (error) {
                            console.error('Error loading notifications:', error);
                        } finally {
                            this.loading = false;
                        }
                    }
                }" x-init="loadNotifications();
                setInterval(() => loadNotifications(), 30000)">

                    <!-- Bell Icon Button -->
                    <button @click="open = !open"
                        class="relative text-gray-600 hover:text-indigo-600 cursor-pointer transition-all">
                        <i class="fas fa-bell text-lg"></i>
                        <span x-show="count > 0" x-text="count" x-transition
                            class="absolute -top-1 -right-1 bg-red-500 text-white text-xs min-w-[18px] h-[18px] rounded-full flex items-center justify-center font-semibold px-1 shadow-lg">
                        </span>
                    </button>

                    <!-- Dropdown Panel -->
                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95 -translate-y-2"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-3 w-80 md:w-96 bg-white border border-gray-200 rounded-xl shadow-2xl z-50 max-h-[500px] overflow-hidden flex flex-col">

                        <!-- Header -->
                        <div class="px-5 py-4 border-b bg-gradient-to-r from-indigo-600 to-blue-600 text-white">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bell text-lg"></i>
                                    <h3 class="font-bold text-base">Notifikasi</h3>
                                </div>
                                <span x-show="count > 0" x-text="count + ' baru'"
                                    class="text-xs bg-white/20 px-3 py-1 rounded-full font-medium">
                                </span>
                            </div>
                        </div>

                        <!-- Loading State -->
                        <div x-show="loading" class="p-8 text-center">
                            <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-indigo-600 mx-auto">
                            </div>
                            <p class="text-sm text-gray-500 mt-3">Memuat notifikasi...</p>
                        </div>

                        <!-- Content -->
                        <div x-show="!loading" class="overflow-y-auto flex-1 bg-gray-50">
                            <!-- Empty State -->
                            <template x-if="notifications.length === 0">
                                <div class="p-10 text-center">
                                    <div
                                        class="inline-flex items-center justify-center w-16 h-16 bg-gray-200 rounded-full mb-3">
                                        <i class="fas fa-bell-slash text-2xl text-gray-400"></i>
                                    </div>
                                    <p class="text-sm text-gray-500 font-medium">Tidak ada notifikasi</p>
                                    <p class="text-xs text-gray-400 mt-1">Anda sudah up to date!</p>
                                </div>
                            </template>

                            <!-- Notification Items -->
                            <template x-for="(notif, index) in notifications" :key="index">
                                <a :href="notif.link" @click="open = false"
                                    class="block px-5 py-4 hover:bg-white border-b border-gray-200 last:border-b-0 transition-all cursor-pointer group">
                                    <div class="flex items-start gap-3">
                                        <!-- Icon Badge -->
                                        <div :class="{
                                            'bg-red-100 text-red-600 ring-red-200': notif.type === 'danger',
                                            'bg-yellow-100 text-yellow-600 ring-yellow-200': notif.type === 'warning',
                                            'bg-blue-100 text-blue-600 ring-blue-200': notif.type === 'info',
                                            'bg-green-100 text-green-600 ring-green-200': notif.type === 'success'
                                        }"
                                            class="w-11 h-11 rounded-xl flex items-center justify-center flex-shrink-0 ring-2 ring-offset-1 transition-transform group-hover:scale-110">
                                            <i :class="'fas ' + notif.icon" class="text-base"></i>
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-bold text-gray-800 mb-1 group-hover:text-indigo-600 transition-colors"
                                                x-text="notif.title"></p>
                                            <p class="text-xs text-gray-600 leading-relaxed mb-2"
                                                x-text="notif.message"></p>
                                            <div class="flex items-center gap-2">
                                                <i class="fas fa-clock text-[10px] text-gray-400"></i>
                                                <p class="text-[10px] text-gray-400 font-medium" x-text="notif.time">
                                                </p>
                                            </div>
                                        </div>

                                        <!-- Arrow -->
                                        <div class="opacity-0 group-hover:opacity-100 transition-opacity">
                                            <i class="fas fa-chevron-right text-xs text-gray-400"></i>
                                        </div>
                                    </div>
                                </a>
                            </template>
                        </div>

                        <!-- Footer -->
                        <div class="px-5 py-3 border-t bg-white">
                            <button @click="loadNotifications()"
                                class="w-full text-center text-xs text-indigo-600 hover:text-indigo-700 font-semibold py-2 hover:bg-indigo-50 rounded-lg transition-colors cursor-pointer">
                                <i class="fas fa-sync-alt mr-1" :class="{ 'animate-spin': loading }"></i>
                                <span x-text="loading ? 'Memuat...' : 'Refresh Notifikasi'"></span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none cursor-pointer">
                        <img src="{{ Auth::user()->foto
                            ? asset('storage/' . Auth::user()->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                            alt="avatar" class="w-8 h-8 rounded-full object-cover">
                        <span
                            class="hidden md:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                        @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
                        <button type="button" onclick="showProfile()"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                            <i class="fas fa-user mr-2"></i> Profil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" onclick="confirmLogout()"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Content Section -->
        <section class="flex-1 p-4 md:p-8 overflow-y-auto">
            @hasSection('breadcrumb')
                <div class="mb-4">
                    @yield('breadcrumb')
                </div>
            @endif
            @yield('content')
        </section>
    </main>

    <!-- Scripts -->
    <script>
        function updateDateTime() {
            const now = new Date();
            const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September',
                'Oktober', 'November', 'Desember'
            ];
            const formatted =
                `${days[now.getDay()]}, ${String(now.getDate()).padStart(2, '0')} ${months[now.getMonth()]} ${now.getFullYear()} | ${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}:${String(now.getSeconds()).padStart(2, '0')}`;
            const datetimeEl = document.getElementById('datetime');
            if (datetimeEl) datetimeEl.innerText = formatted;
        }
        setInterval(updateDateTime, 1000);
        updateDateTime();
    </script>

    <script>
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: @json(session('success')),
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: @json(session('error')),
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif
    </script>

    <script>
        function showProfile() {
            Swal.fire({
                title: 'Profil Saya',
                html: `
        <div class="flex flex-col items-center space-y-6">
            <!-- Foto Profil Full Size -->
            <div class="relative group w-48 h-48">
                <img id="profileImage"
                    src="{{ Auth::user()->foto
                        ? asset('storage/' . Auth::user()->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&size=200' }}"
                    alt="avatar" class="w-48 h-48 rounded-2xl shadow-xl object-cover border-4 border-indigo-100 cursor-pointer transition-transform hover:scale-105"
                    onclick="viewFullImage(this.src)">
                <button type="button"
                    onclick="document.getElementById('inputFoto').click()"
                    class="cursor-pointer absolute bottom-2 right-2 bg-indigo-600 text-white p-3 rounded-full shadow-lg opacity-0 group-hover:opacity-100 transition-all hover:bg-indigo-700 hover:scale-110">
                    <i class="fas fa-camera text-sm"></i>
                </button>
                <div class="absolute top-2 right-2 bg-white text-indigo-600 px-2 py-1 rounded-full text-xs font-semibold shadow">
                    {{ ucfirst(Auth::user()->role) }}
                </div>
            </div>
            
            <!-- Form Upload -->
            <form id="form-update-photo" action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="inputFoto" name="foto" accept="image/*" class="hidden"
                    onchange="previewImage(this)">
            </form>
            
            <!-- Info Profil -->
            <div class="w-full bg-gradient-to-r from-indigo-50 to-blue-50 rounded-xl p-6 space-y-3">
                <div class="flex items-center gap-3 pb-3 border-b border-indigo-200">
                    <i class="fas fa-user text-indigo-600"></i>
                    <div class="text-left flex-1">
                        <p class="text-xs text-gray-500">Nama Lengkap</p>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 pb-3 border-b border-indigo-200">
                    <i class="fas fa-envelope text-indigo-600"></i>
                    <div class="text-left flex-1">
                        <p class="text-xs text-gray-500">Email</p>
                        <p class="font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <i class="fas fa-id-badge text-indigo-600"></i>
                    <div class="text-left flex-1">
                        <p class="text-xs text-gray-500">Role</p>
                        <p class="font-semibold text-gray-800">{{ ucfirst(Auth::user()->role) }}</p>
                    </div>
                </div>
            </div>
            
            <p class="text-xs text-gray-500 italic">
                <i class="fas fa-info-circle mr-1"></i>
                Klik foto untuk melihat ukuran penuh
            </p>
        </div>
    `,
                showConfirmButton: false,
                width: 500,
                customClass: {
                    popup: 'rounded-3xl shadow-2xl'
                },
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = "flex justify-between gap-3 mt-6 w-full";
                    btnWrapper.innerHTML = `
            <button type="button" id="btnKembali"
                class="cursor-pointer flex-1 px-5 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-medium transition-all hover:shadow-md">
                <i class="fas fa-arrow-left mr-2"></i>Kembali
            </button>
            <button type="button" id="btnSimpan"
                class="cursor-pointer flex-1 px-5 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-medium transition-all hover:shadow-md hidden">
                <i class="fas fa-save mr-2"></i>Simpan Perubahan
            </button>
        `;
                    content.appendChild(btnWrapper);
                    document.getElementById('btnKembali').addEventListener('click', () => Swal.close());
                    document.getElementById('btnSimpan').addEventListener('click', () => {
                        document.getElementById('form-update-photo').submit();
                    });
                }
            })
        }

        function viewFullImage(src) {
            Swal.fire({
                imageUrl: src,
                imageAlt: 'Foto Profil',
                showConfirmButton: false,
                showCloseButton: true,
                background: 'rgba(0, 0, 0, 0.95)',
                backdrop: 'rgba(0, 0, 0, 0.8)',
                customClass: {
                    image: 'max-h-screen object-contain rounded-2xl',
                    closeButton: 'text-white hover:text-gray-300'
                }
            });
        }

        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('profileImage').src = e.target.result;
                };
                reader.readAsDataURL(input.files[0]);
                document.getElementById('btnSimpan').classList.remove('hidden');
            }
        }
    </script>

    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Yakin ingin logout?',
                text: "Anda akan keluar dari sesi ini.",
                icon: 'warning',
                iconColor: 'red',
                showCancelButton: true,
                confirmButtonColor: 'red',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            })
        }
    </script>
</body>

</html>
