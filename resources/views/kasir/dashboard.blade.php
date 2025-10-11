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
            <i class="fas fa-cash-register text-indigo-600"></i>
            Dashboard Kasir
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ Auth::user()->name }}! Berikut ringkasan aktivitas kasir hari ini.</p>
    </div>

    <!-- Statistik Cards Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Transaksi Hari Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Transaksi Hari Ini</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $transaksiHariIni ?? 0 }}</h3>
                    <p class="text-xs opacity-75">Total Transaksi</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-shopping-cart text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Pendapatan Hari Ini</p>
                    <h3 class="text-3xl font-bold mb-1">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75">Total Pemasukan</p>
                </div>
                <div class=" bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Buku Terjual -->
        <div class="bg-gradient-to-br from-indigo-500 to-indigo-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Buku Terjual</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $bukuTerjual ?? 0 }}</h3>
                    <p class="text-xs opacity-75">Item Terjual Hari Ini</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-book text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Buku -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Stok Buku</p>
                    <h3 class="text-3xl font-bold mb-1">{{ $totalBuku ?? 0 }}</h3>
                    <p class="text-xs opacity-75">Stok Tersedia</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-boxes text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <a href="{{ route('kasir.buku.index') ?? '#' }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-blue-100 rounded-lg flex items-center justify-center group-hover:bg-blue-600 transition">
                    <i class="fas fa-plus text-2xl text-blue-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Transaksi Baru</h3>
                    <p class="text-sm text-gray-600">Mulai transaksi kasir</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('kasir.buku.index') ?? '#' }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-purple-100 rounded-lg flex items-center justify-center group-hover:bg-purple-600 transition">
                    <i class="fas fa-book-open text-2xl text-purple-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Cek Stok Buku</h3>
                    <p class="text-sm text-gray-600">Lihat ketersediaan buku</p>
                </div>
            </div>
        </a>
        
        <a href="{{ route('kasir.riwayat_transaksi.index') ?? '#' }}" class="bg-white rounded-xl shadow-md border p-6 hover:shadow-lg transition group">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-green-100 rounded-lg flex items-center justify-center group-hover:bg-green-600 transition">
                    <i class="fas fa-history text-2xl text-green-600 group-hover:text-white transition"></i>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-800">Riwayat Transaksi</h3>
                    <p class="text-sm text-gray-600">Lihat semua transaksi</p>
                </div>
            </div>
        </a>
    </div>

    <!-- Grafik dan Stok Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Penjualan (2 kolom) -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-bar text-indigo-600"></i>
                Grafik Penjualan 7 Hari Terakhir
            </h2>
            <div class="relative h-64">
                <canvas id="chartPenjualan"></canvas>
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
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse ($stokHabis ?? [] as $buku)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200 hover:shadow-md transition">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $buku->judul }}">{{ $buku->judul }}</p>
                            <p class="text-xs text-gray-600">{{ $buku->kategori->kategori ?? '-' }}</p>
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
                <div class="space-y-2 max-h-48 overflow-y-auto">
                    @forelse ($stokMenipis ?? [] as $buku)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 hover:shadow-md transition">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-800 truncate" title="{{ $buku->judul }}">{{ $buku->judul }}</p>
                            <p class="text-xs text-gray-600">{{ $buku->kategori->kategori ?? '-' }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xl font-bold text-yellow-600">{{ $buku->stok ?? 0 }}</p>
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

    <!-- Buku Terlaris & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Buku Terlaris -->
        <div class="bg-white rounded-xl shadow-md border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-fire text-red-500"></i>
                Buku Terlaris Minggu Ini
            </h2>
            <div class="space-y-3">
                @forelse ($bukuTerlaris ?? [] as $index => $buku)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100 hover:shadow-md transition">
                    <!-- Badge Ranking -->
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                        {{ $index + 1 }}
                    </div>
                    
                    <!-- Info Buku -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate" title="{{ $buku->judul }}">
                            {{ $buku->judul }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">{{ $buku->penulis ?? 'Penulis' }}</p>
                    </div>
                    
                    <!-- Jumlah Terjual -->
                    <div class="flex-shrink-0 text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $buku->total_terjual ?? 0 }}</p>
                        <p class="text-xs text-gray-500">Terjual</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>Belum ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white rounded-xl shadow-md border overflow-hidden">
            <div class="p-6 border-b bg-gray-50">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class="fas fa-receipt text-indigo-600"></i>
                        Transaksi Terbaru
                    </h2>
                    <a href="{{ route('kasir.transaksi.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                        Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kode</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($transaksiTerbaru ?? [] as $t)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-medium text-gray-800">{{ $t->kode_transaksi }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-900">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-green-600">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                                <i class="fas fa-inbox text-3xl mb-2"></i>
                                <p>Belum ada transaksi hari ini.</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Script Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('chartPenjualan');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Transaksi',
            data: {!! json_encode($grafikData ?? [5, 7, 6, 9, 10, 4, 8]) !!},
            backgroundColor: 'rgb(79, 70, 229)',
            borderRadius: 8,
            barThickness: 40
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.parsed.y + ' Transaksi';
                    }
                }
            }
        },
        scales: {
            y: { 
                beginAtZero: true, 
                ticks: { stepSize: 2 }
            }
        }
    }
});
</script>
@endsection