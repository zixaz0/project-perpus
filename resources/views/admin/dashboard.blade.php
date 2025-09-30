@extends('layouts.app')
@section('content')
    <div class="container mx-auto px-6 py-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">Dashboard</h1>
                <p class="text-gray-600">Halo {{ auth()->user()->name }}! Selamat datang di dashboard admin.</p>
            </div>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Buku -->
            <div class="bg-blue-500 text-white rounded-lg shadow p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Total Buku</h2>
                        <p class="text-3xl font-bold">{{ $totalBuku }}</p>
                    </div>
                    <i class="fas fa-book text-5xl opacity-70"></i>
                </div>
                <a href="{{ route('admin.management_buku') }}"
                    class="mt-4 inline-block text-sm text-blue-100 hover:text-white underline">
                    Lihat selengkapnya<i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Total Kasir -->
            <div class="bg-green-600 text-white rounded-lg shadow p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Total Kasir</h2>
                        <p class="text-3xl font-bold">{{ $totalKasir }}</p>
                    </div>
                    <i class="fas fa-users text-5xl opacity-70"></i>
                </div>
                <a href="{{ route('kasir.index') }}"
                    class="mt-4 inline-block text-sm text-green-100 hover:text-white underline">
                    Lihat selengkapnya<i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>

            <!-- Total Kategori -->
            <div class="bg-orange-600 text-white rounded-lg shadow p-6 flex flex-col justify-between">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">Total Kategori</h2>
                        <p class="text-3xl font-bold">{{ $totalKategori }}</p>
                    </div>
                    <i class="fas fa-tags text-5xl opacity-70"></i>
                </div>
                <a href="{{ route('admin.kategori.index') }}"
                    class="mt-4 inline-block text-sm text-orange-100 hover:text-white underline">
                    Lihat selengkapnya<i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="flex flex-wrap items-center justify-between mb-4 gap-3">
            <h2 class="text-2xl font-bold text-gray-800">Daftar Buku</h2>

            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-2">
                <!-- Search -->
                <input type="text" id="searchCard" name="q" value="{{ request('q') }}"
                    placeholder="Cari judul / kode..."
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                <!-- Dropdown kategori - jenis -->
                <select name="kategori_id"
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategori as $group => $items)
                        <optgroup label="{{ $group }}">
                            @foreach ($items as $kat)
                                <option value="{{ $kat->id }}"
                                    {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->jenis }}
                                </option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>

                <!-- Tombol cari -->
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md cursor-pointer">
                    <i class="fa fa-search"></i>
                </button>
            </form>
        </div>

        <!-- Grid Buku -->
        <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($buku as $item)
                <div class="book-card bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col"
                     data-title="{{ strtolower($item->judul_buku) }} {{ strtolower($item->kode_buku) }} {{ strtolower($item->kategori->kategori ?? '') }}">
                    <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover {{ $item->judul_buku }}"
                        class="w-full h-40 object-cover rounded mb-4">

                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $item->judul_buku }}</h3>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-tag text-blue-500"></i>
                        {{ $item->kategori->kategori ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-calendar text-green-500"></i>
                        {{ $item->tahun_terbit->format('Y') }}
                    </p>

                    <button onclick="showDetail({{ $item->id }})"
                        class="cursor-pointer mt-auto w-full bg-blue-500 hover:bg-blue-600 text-white py-2 rounded-lg shadow transition">
                        Detail
                    </button>
                </div>
            @empty
                <p class="col-span-4 text-center text-gray-500">Belum ada buku.</p>
            @endforelse
        </div>
    </div>

    <!-- Modal Detail Buku -->
    <script>
        function showDetail(id) {
            const buku = @json($buku);

            let item = buku.find(b => b.id === id);
            if (!item) return;

            Swal.fire({
                title: `<h2 class="text-xl font-bold text-gray-800 mb-4">${item.judul_buku}</h2>`,
                html: `
                <div class="flex flex-col md:flex-row items-start gap-6">
                    <div class="flex-shrink-0">
                        <img src="/storage/${item.cover_buku}" 
                             class="w-40 h-56 object-cover rounded-lg shadow-md border">
                    </div>
                    <div class="hidden md:block w-px bg-gray-200"></div>
                    <div class="text-left space-y-3 text-sm md:text-base">
                        <p><i class="fas fa-barcode text-indigo-600"></i> <b>Kode:</b> ${item.kode_buku}</p>
                        <p><i class="fas fa-building text-indigo-600"></i> <b>Penerbit:</b> ${item.penerbit}</p>
                        <p><i class="fas fa-user text-indigo-600"></i> <b>Pengarang:</b> ${item.pengarang}</p>
                        <p><i class="fas fa-tags text-indigo-600"></i> <b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</p>
                        <p><i class="fas fa-list text-indigo-600"></i> <b>Jenis :</b> ${item.kategori ? item.kategori.jenis : '-'}</p>
                        <p><i class="fas fa-calendar text-indigo-600"></i> <b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</p>
                    </div>
                </div>
            `,
                width: 700,
                showCloseButton: true,
                confirmButtonText: "Tutup",
                confirmButtonColor: "#2563eb",
                background: "#f9fafb",
                customClass: {
                    popup: 'rounded-2xl shadow-lg p-6'
                }
            });
        }

        // 🔎 Search Realtime Card
        document.getElementById("searchCard").addEventListener("keyup", function () {
            let keyword = this.value.toLowerCase();
            let cards = document.querySelectorAll(".book-card");

            cards.forEach(card => {
                let text = card.getAttribute("data-title");
                if (text.includes(keyword)) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        });
    </script>
@endsection