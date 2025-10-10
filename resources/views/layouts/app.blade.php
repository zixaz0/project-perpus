<!DOCTYPE html>
<html lang="en" class="scroll-smooth overflow-x-hidden">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BukuKita')</title>

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
}" x-init="
    $watch('sidebarOpen', val => localStorage.setItem('sidebarOpen', JSON.stringify(val)));
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            mobileMenuOpen = false;
            sidebarOpen = JSON.parse(localStorage.getItem('sidebarOpen')) ?? true;
        } else {
            sidebarOpen = false;
        }
    });
">

    <!-- Mobile Overlay -->
    <div x-show="mobileMenuOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="closeMobileMenu()"
         class="fixed inset-0 bg-black bg-opacity-50 z-30 md:hidden"
         x-cloak>
    </div>

    <!-- Sidebar -->
    <aside :class="{
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
                <a href="{{ route('admin.riwayat_transaksi.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
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
                <a href="{{ route('kasir.riwayat_transaksi.index') }}" @click="window.innerWidth < 768 && closeMobileMenu()"
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
                   class="tooltip flex items-center gap-3 p-3 rounded-lg transition hover:bg-indigo-50 {{ request()->routeIs('owner.laporan_penjualan') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
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
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 md:px-6 shadow-sm">
            <button @click="toggleSidebar()" class="cursor-pointer text-gray-600 hover:text-indigo-600">
                <i class="fas fa-bars text-xl"></i>
            </button>
            
            <!-- Desktop: Waktu -->
            <div class="hidden md:block text-lg font-semibold text-gray-700" id="datetime"></div>

            <!-- User Info -->
            <div class="flex items-center gap-2 md:gap-4">
                <!-- Notifikasi -->
                <button class="relative text-gray-600 hover:text-indigo-600 cursor-pointer">
                    <i class="fas fa-bell text-lg"></i>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none cursor-pointer">
                        <img src="{{ Auth::user()->foto
                            ? asset('storage/' . Auth::user()->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                            alt="avatar" class="w-8 h-8 rounded-full object-cover">
                        <span class="hidden md:inline text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
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
        <div class="flex flex-col items-center space-y-4">
            <div class="relative group w-24 h-24">
                <img id="profileImage"
                    src="{{ Auth::user()->foto
                        ? asset('storage/' . Auth::user()->foto)
                        : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&size=100' }}"
                    alt="avatar" class="w-24 h-24 rounded-full shadow-md object-cover">
                <button type="button"
                    onclick="document.getElementById('inputFoto').click()"
                    class="cursor-pointer absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full shadow opacity-0 group-hover:opacity-100 transition">
                    <i class="fas fa-pencil-alt text-xs"></i>
                </button>
            </div>
            <form id="form-update-photo" action="{{ route('profile.updatePhoto') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="inputFoto" name="foto" accept="image/*" class="hidden"
                    onchange="previewImage(this)">
            </form>
            <div class="text-left space-y-2 w-full">
                <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                <p><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
            </div>
        </div>
    `,
                showConfirmButton: false,
                width: 400,
                customClass: {
                    popup: 'rounded-2xl shadow-lg'
                },
                didOpen: () => {
                    const content = Swal.getHtmlContainer();
                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = "flex justify-between gap-3 mt-6 w-full";
                    btnWrapper.innerHTML = `
            <button type="button" id="btnKembali"
                class="cursor-pointer flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                Kembali
            </button>
            <button type="button" id="btnSimpan"
                class="cursor-pointer flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 hidden">
                Simpan Perubahan
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