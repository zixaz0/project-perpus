@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="flex items-center space-x-2">
            <li>
                <span class="text-indigo-600 font-medium">Dashboard</span>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-6 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-tachometer-alt text-indigo-600"></i>
            Dashboard Admin
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ Auth::user()->name }}! Kelola data buku dan sistem toko Anda.</p>
    </div>

    <!-- Statistik Cards Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Buku -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Buku</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $totalBuku }}</h3>
                    <a href="{{ route('admin.buku.index') }}" class="text-xs opacity-75 hover:opacity-100 inline-flex items-center gap-1">
                        Lihat selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-book text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kasir -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Kasir</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $totalKasir }}</h3>
                    <a href="{{ route('kasir.index') }}" class="text-xs opacity-75 hover:opacity-100 inline-flex items-center gap-1">
                        Lihat selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Kategori -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Kategori</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $totalKategori }}</h3>
                    <a href="{{ route('admin.kategori.index') }}" class="text-xs opacity-75 hover:opacity-100 inline-flex items-center gap-1">
                        Lihat selengkapnya <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-tags text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <a href="{{ route('admin.buku.create') }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition">
                    <i class="fas fa-book-medical text-2xl text-blue-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah<br>Buku</br></h3>
                    <p class="text-sm text-gray-600">Tambah buku baru</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('admin.kategori.create') }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-600 transition">
                    <i class="fas fa-tags text-2xl text-purple-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Kategori</h3>
                    <p class="text-sm text-gray-600">Buat kategori baru</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('kasir.create') }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-600 transition">
                    <i class="fas fa-user-plus text-2xl text-green-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Tambah Pegawai</h3>
                    <p class="text-sm text-gray-600">Daftarkan pegawai</p>
                </div>
            </div>
        </a>

        <a href="{{ route('admin.stok_harga.index') }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-orange-100 rounded-lg flex items-center justify-center group-hover:bg-orange-600 transition">
                    <i class="fas fa-boxes text-2xl text-orange-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Kelola<br>Stok</br></h3>
                    <p class="text-sm text-gray-600">Update stok & harga</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Grafik dan Stok Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Distribusi (2 kolom) -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Grafik Distribusi Kategori -->
            <div class="bg-white rounded-xl shadow-md border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-pie text-indigo-600"></i>
                    Distribusi Buku per Kategori
                </h2>
                <div class="relative h-64">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>

            <!-- Grafik Stok Buku -->
            <div class="bg-white rounded-xl shadow-md border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <i class="fas fa-chart-bar text-indigo-600"></i>
                    Status Stok Buku
                </h2>
                <div class="relative h-64">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Stok Alert (1 kolom) -->
        <div class="space-y-4">
            <!-- Stok Habis -->
            <div class="bg-white rounded-xl shadow-md border p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i>
                    Stok Habis
                </h2>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse ($stokHabis ?? [] as $item)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200 hover:shadow-md transition">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                                 alt="{{ $item->judul_buku }}"
                                 class="w-10 h-14 object-cover rounded shadow border-2 border-white opacity-75">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $item->judul_buku }}">
                                {{ $item->judul_buku }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $item->kategori->kategori ?? '-' }}</p>
                        </div>
                        <span class="flex-shrink-0 inline-flex items-center px-2 py-1 rounded-full text-xs font-bold bg-red-500 text-white">
                            <i class="fas fa-ban mr-1"></i> 0
                        </span>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-smile-beam text-3xl mb-2 text-green-500"></i>
                        <p class="text-sm">Tidak ada stok habis!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stok Menipis -->
            <div class="bg-white rounded-xl shadow-md border p-4">
                <h2 class="text-lg font-bold text-gray-800 mb-3 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    Stok Menipis
                </h2>
                <div class="space-y-2 max-h-64 overflow-y-auto">
                    @forelse ($stokMenipis ?? [] as $item)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 hover:shadow-md transition">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                                 alt="{{ $item->judul_buku }}"
                                 class="w-10 h-14 object-cover rounded shadow border-2 border-white">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $item->judul_buku }}">
                                {{ $item->judul_buku }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $item->kategori->kategori ?? '-' }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xl font-bold text-yellow-600">{{ $item->stokHarga->stok ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Sisa</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-check-circle text-3xl mb-2 text-green-500"></i>
                        <p class="text-sm">Semua stok aman!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-md border p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-box-open text-purple-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Stok Tersedia</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $buku->where('stokHarga.stok', '>', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Stok Habis</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $buku->where('stokHarga.stok', '<=', 0)->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Buku Terbaru</p>
                    <p class="text-2xl font-bold text-gray-800">{{ $buku->where('created_at', '>=', now()->subMonth())->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-md border p-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i class="fas fa-star text-indigo-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Total Nilai Stok</p>
                    <p class="text-lg font-bold text-gray-800">Rp {{ number_format($buku->sum(function($b) { return ($b->stokHarga->stok ?? 0) * ($b->stokHarga->harga ?? 0); }), 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filter & Search -->
    <div class="bg-white rounded-xl shadow-md border p-6 mb-6">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fas fa-books text-indigo-600"></i>
                Daftar Buku
            </h2>

            <form action="{{ route('admin.dashboard') }}" method="GET" class="flex flex-wrap items-center gap-3">
                <!-- Toggle -->
                <label class="flex items-center space-x-2 bg-gray-50 px-4 py-2.5 rounded-lg border border-gray-200 cursor-pointer hover:bg-gray-100 transition">
                    <input type="checkbox" id="toggleAvailable" class="h-4 w-4 text-indigo-600 rounded cursor-pointer" checked>
                    <span class="text-sm text-gray-700 font-medium">Hanya tersedia</span>
                </label>

                <!-- Search -->
                <input type="text" id="searchCard" name="q" value="{{ request('q') }}"
                    placeholder="Cari judul / kode..."
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">

                <!-- Kategori -->
                <select name="kategori_id"
                    class="px-4 py-2.5 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 cursor-pointer bg-white">
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

                <!-- Button -->
                <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-2.5 rounded-lg cursor-pointer transition flex items-center gap-2 font-medium">
                    <i class="fas fa-search"></i>
                    <span>Cari</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Grid Buku -->
    <div id="bookGrid" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @forelse($buku as $item)
            <div class="book-card relative bg-white rounded-xl shadow-md hover:shadow-xl transition-all duration-300 p-4 flex flex-col border
        {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'opacity-60' : 'hover:-translate-y-1' }}"
                data-title="{{ strtolower($item->judul_buku) }} {{ strtolower($item->kode_buku) }} {{ strtolower($item->kategori->kategori ?? '') }}"
                data-stok="{{ $item->stokHarga->stok ?? 0 }}">

                <div class="relative w-full mb-4" style="padding-top: 150%;">
                    <img src="{{ asset('storage/' . $item->cover_buku) }}" alt="cover {{ $item->judul_buku }}"
                        class="absolute inset-0 w-full h-full object-cover rounded-lg 
                                {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'opacity-40' : '' }}">

                    @if (($item->stokHarga->stok ?? 0) <= 0)
                        <div class="absolute inset-0 flex items-center justify-center rounded-lg">
                            <span class="bg-red-500 text-white text-sm font-bold px-4 py-2 rounded-full shadow-lg">
                                <i class="fas fa-ban mr-1"></i> Stok Habis
                            </span>
                        </div>
                    @else
                        <div class="absolute top-2 right-2 bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                            {{ $item->stokHarga->stok }} pcs
                        </div>
                    @endif
                </div>

                <h3 class="font-bold text-lg text-gray-800 mb-3 line-clamp-2">{{ $item->judul_buku }}</h3>
                
                <div class="space-y-2 mb-4">
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-tag text-blue-500 w-4"></i>
                        <span class="truncate">{{ $item->kategori->kategori ?? '-' }}</span>
                    </p>
                    <p class="text-sm text-gray-600 flex items-center gap-2">
                        <i class="fas fa-calendar text-green-500 w-4"></i>
                        {{ $item->tahun_terbit->format('Y') }}
                    </p>
                    <p class="text-lg font-bold text-indigo-600 flex items-center gap-2">
                        <i class="fas fa-money-bill-wave w-4"></i>
                        {{ $item->stokHarga ? 'Rp ' . number_format($item->stokHarga->harga, 0, ',', '.') : '-' }}
                    </p>
                </div>

                <button onclick="showDetail({{ $item->id }})"
                    class="mt-auto w-full py-2.5 rounded-lg shadow-md transition-all duration-200 font-medium
            {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'bg-gray-400 cursor-not-allowed' : 'cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white hover:shadow-lg' }}">
                    {{ ($item->stokHarga->stok ?? 0) <= 0 ? 'Tidak Tersedia' : 'Lihat Detail' }}
                </button>
            </div>
        @empty
            <div class="col-span-4 text-center py-16">
                <i class="fas fa-inbox text-gray-300 text-6xl mb-4"></i>
                <p class="text-gray-500 text-lg">Belum ada buku.</p>
            </div>
        @endforelse
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Data untuk grafik
    const bukuData = @json($buku);
    
    // Grafik Distribusi Kategori (Pie Chart)
    const categoryCount = {};
    bukuData.forEach(book => {
        const kategori = book.kategori?.kategori || 'Tidak ada kategori';
        categoryCount[kategori] = (categoryCount[kategori] || 0) + 1;
    });

    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: Object.keys(categoryCount),
            datasets: [{
                data: Object.values(categoryCount),
                backgroundColor: [
                    '#3B82F6', '#10B981', '#F59E0B', '#EF4444', '#8B5CF6',
                    '#EC4899', '#14B8A6', '#F97316', '#06B6D4', '#84CC16'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: { size: 12 }
                    }
                }
            }
        }
    });

    // Grafik Status Stok (Bar Chart)
    const stokTersedia = bukuData.filter(b => (b.stok_harga?.stok ?? 0) > 10).length;
    const stokRendah = bukuData.filter(b => {
        const stok = b.stok_harga?.stok ?? 0;
        return stok > 0 && stok <= 10;
    }).length;
    const stokHabis = bukuData.filter(b => (b.stok_harga?.stok ?? 0) <= 0).length;

    const stockCtx = document.getElementById('stockChart').getContext('2d');
    new Chart(stockCtx, {
        type: 'bar',
        data: {
            labels: ['Stok Aman (>10)', 'Stok Rendah (1-10)', 'Stok Habis'],
            datasets: [{
                label: 'Jumlah Buku',
                data: [stokTersedia, stokRendah, stokHabis],
                backgroundColor: ['#10B981', '#F59E0B', '#EF4444'],
                borderRadius: 8,
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Modal Detail Buku
    function showDetail(id) {
        const buku = @json($buku);
        let item = buku.find(b => b.id === id);
        if (!item) return;

        Swal.fire({
            width: 720,
            showCloseButton: true,
            confirmButtonText: "Tutup",
            confirmButtonColor: "#4F46E5",
            background: "#f9fafb",
            customClass: {
                popup: 'rounded-2xl shadow-2xl p-6'
            },
            html: `
            <div class="flex flex-col md:flex-row items-start gap-6">
                <div class="flex-shrink-0 mx-auto md:mx-0">
                    <img src="/storage/${item.cover_buku}" 
                         class="w-44 h-64 object-cover rounded-xl shadow-lg border-2 border-gray-200">
                </div>
                <div class="hidden md:block w-px bg-gray-300"></div>
                <div class="flex-1 text-left space-y-4">
                    <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-indigo-500 pb-2">
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
                            <span><b>Tahun:</b> ${new Date(item.tahun_terbit).getFullYear()}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-cubes text-indigo-600"></i> 
                            <span><b>Stok:</b> ${item.stok_harga ? item.stok_harga.stok + " pcs" : '-'}</span>
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-money-bill-wave text-indigo-600"></i> 
                            <span><b>Harga:</b> ${item.stok_harga ? 'Rp ' + new Intl.NumberFormat('id-ID').format(item.stok_harga.harga) : '-'}</span>
                        </p>
                    </div>
                </div>
            </div>
        `
        });
    }

    // Filter & Search
    const searchInput = document.getElementById("searchCard");
    const toggleAvailable = document.getElementById("toggleAvailable");

    function filterBooks() {
        let keyword = searchInput.value.toLowerCase();
        let onlyAvailable = toggleAvailable.checked;
        let cards = document.querySelectorAll(".book-card");

        cards.forEach(card => {
            let text = card.getAttribute("data-title");
            let stok = parseInt(card.getAttribute("data-stok")) || 0;

            let matchSearch = text.includes(keyword);
            let matchStok = onlyAvailable ? stok > 0 : true;

            if (matchSearch && matchStok) {
                card.style.display = "flex";
            } else {
                card.style.display = "none";
            }
        });
    }

    searchInput.addEventListener("keyup", filterBooks);
    toggleAvailable.addEventListener("change", filterBooks);
    window.addEventListener("DOMContentLoaded", filterBooks);
</script>
@endsection