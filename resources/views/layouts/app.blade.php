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
    <main class="flex-1 ml-64 flex flex-col h-screen">

        <!-- Navbar -->
        <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 shadow-sm">
            <!-- Kiri: Judul Halaman -->
            <div class="text-lg font-semibold text-gray-700" id="datetime">
                <script>
                    function updateDateTime() {
                        const now = new Date();

                        // Format hari dalam bahasa Indonesia
                        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
                        const dayName = days[now.getDay()];

                        // Format tanggal
                        const day = String(now.getDate()).padStart(2, '0');
                        const monthNames = [
                            'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                            'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                        ];
                        const month = monthNames[now.getMonth()];
                        const year = now.getFullYear();

                        // Format jam
                        const hours = String(now.getHours()).padStart(2, '0');
                        const minutes = String(now.getMinutes()).padStart(2, '0');
                        const seconds = String(now.getSeconds()).padStart(2, '0');

                        const formatted = `${dayName}, ${day} ${month} ${year} | ${hours}:${minutes}:${seconds}`;

                        document.getElementById('datetime').innerText = formatted;
                    }

                    // update tiap detik
                    setInterval(updateDateTime, 1000);
                    // pertama kali jalanin langsung
                    updateDateTime();
                </script>
            </div>

            <!-- Kanan: User Info -->
            <div class="flex items-center gap-4">
                <!-- Notifikasi -->
                <button class="relative text-gray-600 hover:text-indigo-600">
                    <i class="fas fa-bell text-lg"></i>
                    <span
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-4 h-4 rounded-full flex items-center justify-center">3</span>
                </button>

                <!-- User Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                        <img src="{{ Auth::user()->foto
                            ? asset('storage/' . Auth::user()->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random' }}"
                            alt="avatar" class="w-8 h-8 rounded-full object-cover">
                        <span class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-500"></i>
                    </button>

                    <!-- Dropdown -->
                    <div x-show="open" @click.away="open = false"
                        class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg py-2 z-50">
                        <button type="button" onclick="showProfile()"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                            <i class="fas fa-user mr-2"></i> Profil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="button" onclick="confirmLogout()"
                                class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                <i class="fas fa-sign-out-alt mr-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Isi Konten -->
        <section class="flex-1 p-8 overflow-y-auto">
            @yield('content')
        </section>
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

    <script>
        function showProfile() {
            Swal.fire({
                title: 'Profil Saya',
                html: `
            <div class="flex flex-col items-center space-y-4">
                <img src="{{ Auth::user()->foto
                    ? asset('storage/' . Auth::user()->foto)
                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&size=100' }}"
                     alt="avatar" class="w-24 h-24 rounded-full shadow-md object-cover">
                
                <div class="text-left space-y-2 w-full">
                    <p><strong>Nama:</strong> {{ Auth::user()->name }}</p>
                    <p><strong>Email:</strong> {{ Auth::user()->email }}</p>
                    <p><strong>Role:</strong> {{ ucfirst(Auth::user()->role) }}</p>
                </div>
            </div>
        `,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#4F46E5',
                customClass: {
                    popup: 'rounded-2xl shadow-lg'
                }
            })
        }

        function showProfile() {
            Swal.fire({
                title: 'Profil Saya',
                html: `
            <div class="flex flex-col items-center space-y-4">

                <!-- Foto + Tombol Edit -->
                <div class="relative group w-24 h-24">
                    <img id="profileImage"
                        src="{{ Auth::user()->foto
                            ? asset('storage/' . Auth::user()->foto)
                            : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=random&size=100' }}"
                        alt="avatar" class="w-24 h-24 rounded-full shadow-md object-cover">

                    <!-- Icon pensil -->
                    <button type="button"
                        onclick="document.getElementById('inputFoto').click()"
                        class="absolute bottom-0 right-0 bg-indigo-600 text-white p-2 rounded-full shadow opacity-0 group-hover:opacity-100 transition">
                        <i class="fas fa-pencil-alt text-xs"></i>
                    </button>
                </div>

                <!-- Input file hidden -->
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
                    // Tambahin tombol manual
                    const content = Swal.getHtmlContainer();

                    const btnWrapper = document.createElement('div');
                    btnWrapper.className = "flex justify-between gap-3 mt-6 w-full";

                    btnWrapper.innerHTML = `
                <button type="button" id="btnKembali"
                    class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    Kembali
                </button>
                <button type="button" id="btnSimpan"
                    class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 hidden">
                    Simpan Perubahan
                </button>
            `;

                    content.appendChild(btnWrapper);

                    // event tombol
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

                // Tampilkan tombol "Simpan Perubahan"
                document.getElementById('btnSimpan').classList.remove('hidden');
            }
        }
    </script>
</body>

</html>
