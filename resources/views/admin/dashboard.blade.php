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
                <a href="{{ route('admin.buku.index') }}"
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
                <!-- Toggle hanya tampilkan stok tersedia -->
                <label class="flex items-center space-x-2">
                    <input type="checkbox" id="toggleAvailable" class="h-4 w-4 text-indigo-600 cursor-pointer" checked>
                    <span class="text-sm text-gray-700">Hanya tampilkan yang tersedia</span>
                </label>

                <!-- Search -->
                <input type="text" id="searchCard" name="q" value="{{ request('q') }}"
                    placeholder="Cari judul / kode..."
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">

                <!-- Dropdown kategori - jenis -->
                <select name="kategori_id"
                    class="px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer">
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
                <div class="book-card relative bg-white rounded-lg shadow hover:shadow-lg transition p-4 flex flex-col
            {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'opacity-50 pointer-events-none' : '' }}"
                    data-title="{{ strtolower($item->judul_buku) }} {{ strtolower($item->kode_buku) }} {{ strtolower($item->kategori->kategori ?? '') }}"
                    data-stok="{{ $item->stokHarga->stok ?? 0 }}">

                    <div class="relative w-full mb-4" style="padding-top: 150%;">
                        <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover {{ $item->judul_buku }}"
                            class="absolute inset-0 w-full h-full object-cover rounded 
                                    {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'opacity-40' : '' }}">

                        {{-- Overlay stok habis (hanya muncul kalau stok 0) --}}
                        @if (($item->stokHarga->stok ?? 0) <= 0)
                            <div class="absolute inset-0 flex items-center justify-center rounded">
                                <span class="bg-red-500 text-white text-sm font-bold px-3 py-1 rounded-full shadow-lg">
                                    Stok Habis
                                </span>
                            </div>
                        @endif
                    </div>
                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $item->judul_buku }}</h3>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-tag text-blue-500"></i>
                        {{ $item->kategori->kategori ?? '-' }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-calendar text-green-500"></i>
                        {{ $item->tahun_terbit->format('Y') }}
                    </p>
                    <p class="text-sm text-gray-600 mb-1">
                        <i class="fas fa-cubes text-purple-500"></i>
                        Stok: {{ $item->stokHarga->stok ?? 0 }}
                    </p>
                    <p class="text-sm text-gray-600 mb-4">
                        <i class="fas fa-dollar-sign text-yellow-500"></i>
                        Harga: {{ $item->stokHarga ? 'Rp ' . number_format($item->stokHarga->harga, 0, ',', '.') : '-' }}
                    </p>

                    {{-- Tombol detail dinonaktifkan jika stok habis --}}
                    <button onclick="showDetail({{ $item->id }})"
                        class="mt-auto w-full py-2 rounded-lg shadow transition
                {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'cursor-pointer bg-blue-500 hover:bg-blue-600 text-white' }}">
                        {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'Tidak Tersedia' : 'Detail' }}
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
                width: 720,
                showCloseButton: true,
                confirmButtonText: "Tutup",
                confirmButtonColor: "#2563eb",
                background: "#f9fafb",
                customClass: {
                    popup: 'rounded-2xl shadow-xl p-6'
                },
                html: `
                <div class="flex flex-col md:flex-row items-start gap-6">

                    <!-- Cover -->
                    <div class="flex-shrink-0 mx-auto md:mx-0 aspect-[2/3]">
                        <img src="/storage/${item.cover_buku}" 
                             class="w-44 h-64 object-cover rounded-xl shadow-lg border border-gray-200">
                    </div>

                    <!-- Divider -->
                    <div class="hidden md:block w-px bg-gray-300"></div>

                    <!-- Detail -->
                    <div class="flex-1 text-left space-y-4 text-sm md:text-base">
                        <h2 class="text-2xl font-bold text-gray-800 border-b pb-2">
                            ${item.judul_buku}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-gray-700">
                            <p class="flex items-center gap-2">
                                <i class="fas fa-barcode text-indigo-600"></i> 
                                <span><b>Kode:</b> ${item.kode_buku}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-building text-indigo-600"></i> 
                                <span><b>Penerbit:</b> ${item.penerbit}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-user text-indigo-600"></i> 
                                <span><b>Pengarang:</b> ${item.pengarang}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-tags text-indigo-600"></i> 
                                <span><b>Kategori:</b> ${item.kategori ? item.kategori.kategori : '-'}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-list text-indigo-600"></i> 
                                <span><b>Jenis:</b> ${item.kategori ? item.kategori.jenis : '-'}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-calendar text-indigo-600"></i> 
                                <span><b>Tahun Terbit:</b> ${new Date(item.tahun_terbit).getFullYear()}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-cubes text-indigo-600"></i> 
                                <span><b>Stok:</b> ${item.stok_harga ? item.stok_harga.stok + " pcs" : '-'}</span>
                            </p>
                            <p class="flex items-center gap-2">
                                <i class="fas fa-dollar-sign text-indigo-600"></i> 
                                <span><b>Harga:</b> ${item.stok_harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.stok_harga.harga) : '-'}</span>
                            </p>
                        </div>
                    </div>
                </div>
            `
            });
        }

        // 🔎 Search Realtime Card
        document.getElementById("searchCard").addEventListener("keyup", function() {
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
    <script>
        const searchInput = document.getElementById("searchCard");
        const toggleAvailable = document.getElementById("toggleAvailable");

        function filterBooks() {
            let keyword = searchInput.value.toLowerCase();
            let onlyAvailable = toggleAvailable.checked;
            let cards = document.querySelectorAll(".book-card");

            cards.forEach(card => {
                let text = card.getAttribute("data-title");
                let stok = parseInt(card.getAttribute("data-stok")) || 0;

                // Filter by search & stok
                let matchSearch = text.includes(keyword);
                let matchStok = onlyAvailable ? stok > 0 : true;

                if (matchSearch && matchStok) {
                    card.style.display = "flex";
                } else {
                    card.style.display = "none";
                }
            });
        }

        // Realtime search + toggle
        searchInput.addEventListener("keyup", filterBooks);
        toggleAvailable.addEventListener("change", filterBooks);

        // Jalankan filter default (hanya stok tersedia)
        window.addEventListener("DOMContentLoaded", filterBooks);
    </script>
@endsection
