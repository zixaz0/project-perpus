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


    <!-- Cloak -->
    <style>
        [x-cloak] {
            display: none !important
        }
    </style>
</head>
@stack('scripts')

<body class="bg-[#F9FAFB] h-screen overflow-x-hidden font-sans flex">

    <!-- Sidebar -->
    <aside
        class="fixed left-0 top-0 w-64 h-full bg-white text-gray-800 flex flex-col shadow-lg border-r border-gray-200">
        <!-- Header -->
        <div class="h-16 flex items-center px-6 border-b border-gray-200">
            <img src="{{ asset('images/logo_doang.png') }}" alt="Logo BukuKita" class="h-10 w-auto object-contain">
            <span class="font-bold text-xl tracking-wide text-blue-500 font-sans">BukuKita</span>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-2 text-sm">
            {{-- ✅ Sidebar untuk ADMIN --}}
            @if (Auth::check() && Auth::user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                  hover:bg-indigo-50 {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-tachometer-alt"></i> Dashboard Admin
                </a>

                <a href="{{ route('admin.management_buku') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                        hover:bg-indigo-50 {{ request()->routeIs('admin.management_buku') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book"></i> Management Buku
                </a>

                <a href="{{ route('admin.management_kasir') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                        hover:bg-indigo-50 {{ request()->routeIs('admin.management_kasir') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-users"></i> Management Kasir
                </a>

                <a href="{{ route('admin.riwayat_transaksi') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                        hover:bg-indigo-50 {{ request()->routeIs('admin.riwayat_transaksi') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-history"></i> Riwayat Transaksi
                </a>
            @endif

            {{-- ✅ Sidebar untuk KASIR --}}
            @if (Auth::check() && Auth::user()->role === 'kasir')
                <a href="{{ route('kasir.dashboard') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('kasir.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-cash-register"></i> Dashboard Kasir
                </a>
                <a href="{{ route('kasir.data_buku') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('kasir.data_buku') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book"></i> Data Buku
                </a>
                <a href="{{ route('kasir.transaksi') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('kasir.transaksi') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-credit-card"></i> Transaksi
                </a>
                <a href="{{ route('kasir.riwayat_transaksi') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('kasir.riwayat_transaksi') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-history"></i> Riwayat Transaksi
                </a>
            @endif

            {{-- ✅ Sidebar untuk OWNER --}}
            @if (Auth::check() && Auth::user()->role === 'owner')
                <a href="{{ route('owner.dashboard') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('owner.dashboard') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-user-tie"></i> Dashboard Owner
                </a>
                <a href="{{ route('owner.data_buku') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('owner.data_buku') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-book"></i> Data Buku
                </a>
                <a href="{{ route('owner.data_pegawai') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('owner.data_pegawai') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-users"></i> Data Pegawai
                </a>
                <a href="{{ route('owner.laporan_penjualan') }}"
                    class="flex items-center gap-3 p-3 rounded-lg transition 
                          hover:bg-indigo-50 {{ request()->routeIs('owner.laporan_penjualan') ? 'bg-indigo-100 text-indigo-700 font-semibold' : 'text-gray-600' }}">
                    <i class="fas fa-chart-bar"></i> Laporan Penjualan
                </a>
            @endif
        </nav>

        <!-- Logout -->
        @if (Auth::check())
            <div class="px-4 pb-6">
                <form id="logout-form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <div class="flex">
                        <button type="button" onclick="confirmLogout()"
                            class="flex items-center justify-center gap-2 px-4 py-2 
                       rounded-md bg-blue-600 hover:bg-blue-700 text-white font-medium transition">
                            <i class="fas fa-sign-out-alt"></i>
                            <span>Logout</span>
                        </button>
                    </div>
                </form>
            </div>
        @endif
    </aside>

    <!-- Konten -->
    <main class="flex-1 ml-64 p-8 overflow-y-auto">
        @yield('content')
    </main>

    <script>
        // ✅ SweetAlert sukses setelah redirect
        @if (session('success'))
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('success') }}",
                icon: 'success',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'OK'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                title: 'Gagal!',
                text: "{{ session('error') }}",
                icon: 'error',
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK'
            });
        @endif
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
