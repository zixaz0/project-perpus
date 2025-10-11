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
            <i class="fas fa-chart-line text-indigo-600"></i>
            Dashboard Owner
        </h1>
        <p class="text-gray-600 mt-2">Selamat datang, {{ Auth::user()->name }}! Berikut ringkasan bisnis toko buku Anda.</p>
    </div>

    <!-- Statistik Cards Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pendapatan Hari Ini -->
        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Pendapatan Hari Ini</p>
                    <h3 class="text-3xl font-bold mb-1">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75">{{ $transaksiCountHariIni }} Transaksi</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-wallet text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Pendapatan Bulan Ini -->
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Pendapatan Bulan Ini</p>
                    <h3 class="text-3xl font-bold mb-1">Rp {{ number_format($totalPendapatanBulanIni, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75">{{ $totalTransaksiBulanIni }} Transaksi</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Buku -->
        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Koleksi Buku</p>
                    <h3 class="text-3xl font-bold mb-1">{{ number_format($totalBuku) }}</h3>
                    <p class="text-xs opacity-75">{{ number_format($totalBukuTerjual) }} Terjual Bulan Ini</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-book text-3xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Pegawai -->
        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-xl shadow-lg p-6 transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm opacity-90 mb-1">Total Pegawai</p>
                    <h3 class="text-3xl font-bold mb-1">{{ number_format($totalPegawai) }}</h3>
                    <p class="text-xs opacity-75">Kasir & Admin</p>
                </div>
                <div class="bg-opacity-20 p-4 rounded-lg">
                    <i class="fas fa-users text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Grafik dan Info Tambahan -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Grafik Pendapatan 7 Hari Terakhir -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-chart-area text-indigo-600"></i>
                Grafik Pendapatan 7 Hari Terakhir
            </h2>
            <div class="relative h-64">
                <canvas id="pendapatanChart"></canvas>
            </div>
        </div>

        <!-- Metode Pembayaran -->
        <div class="bg-white rounded-xl shadow-md border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-credit-card text-indigo-600"></i>
                Metode Pembayaran
            </h2>
            <div class="space-y-4">
                @foreach($metodeBayar as $metode)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $metode->metode_bayar == 'cash' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                            <i class="fas {{ $metode->metode_bayar == 'cash' ? 'fa-money-bill' : 'fa-credit-card' }}"></i>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-800 uppercase">{{ $metode->metode_bayar }}</p>
                            <p class="text-sm text-gray-500">Bulan Ini</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $metode->jumlah }}</p>
                        <p class="text-xs text-gray-500">Transaksi</p>
                    </div>
                </div>
                @endforeach
                
                @if($metodeBayar->isEmpty())
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>Belum ada data</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Buku Terlaris & Stok Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Buku Terlaris -->
        <div class="bg-white rounded-xl shadow-md border p-6">
            <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                <i class="fas fa-fire text-red-500"></i>
                Top 5 Buku Terlaris Bulan Ini
            </h2>
            <div class="space-y-3">
                @forelse($bukuTerlaris as $index => $item)
                <div class="flex items-center gap-4 p-4 bg-gradient-to-r from-indigo-50 to-purple-50 rounded-lg border border-indigo-100 hover:shadow-md transition">
                    <!-- Badge Ranking -->
                    <div class="flex-shrink-0 w-8 h-8 bg-indigo-600 text-white rounded-full flex items-center justify-center font-bold text-sm shadow">
                        {{ $index + 1 }}
                    </div>
                    
                    <!-- Cover Buku -->
                    <div class="flex-shrink-0">
                        <img src="{{ asset('storage/' . $item->buku->cover_buku) }}" 
                             alt="{{ $item->buku->judul_buku }}"
                             class="w-12 h-16 object-cover rounded shadow-md border-2 border-white">
                    </div>
                    
                    <!-- Info Buku -->
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate" title="{{ $item->buku->judul_buku }}">
                            {{ $item->buku->judul_buku }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">
                            <span class="inline-flex items-center">
                                <i class="fas fa-boxes text-indigo-500 mr-1"></i>
                                Stok: <span class="font-semibold ml-1">{{ $item->buku->stokHarga->stok ?? 0 }}</span>
                            </span>
                            <span class="mx-2">|</span>
                            <span class="inline-flex items-center">
                                <i class="fas fa-tag text-green-500 mr-1"></i>
                                Rp {{ number_format($item->buku->stokHarga->harga ?? 0, 0, ',', '.') }}
                            </span>
                        </p>
                    </div>
                    
                    <!-- Jumlah Terjual -->
                    <div class="flex-shrink-0 text-right">
                        <p class="text-2xl font-bold text-indigo-600">{{ $item->total_terjual }}</p>
                        <p class="text-xs text-gray-500">Terjual</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8 text-gray-500">
                    <i class="fas fa-inbox text-3xl mb-2"></i>
                    <p>Belum ada data penjualan</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Stok Alert (Menipis & Habis) -->
        <div class="space-y-6">
            <!-- Stok Menipis -->
            <div class="bg-white rounded-xl shadow-md border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-exclamation-triangle text-yellow-500"></i>
                    Stok Menipis (< 10)
                </h2>
                <div class="space-y-2">
                    @forelse($stokMenipis as $item)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg border border-yellow-200 hover:shadow-md transition">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                                 alt="{{ $item->judul_buku }}"
                                 class="w-10 h-14 object-cover rounded shadow border-2 border-white">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate" title="{{ $item->judul_buku }}">
                                {{ $item->judul_buku }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $item->kategori->kategori }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <p class="text-xl font-bold text-yellow-600">{{ $item->stokHarga->stok ?? 0 }}</p>
                            <p class="text-xs text-gray-500">Sisa</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-check-circle text-2xl mb-1 text-green-500"></i>
                        <p class="text-sm">Semua stok aman!</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Stok Habis -->
            <div class="bg-white rounded-xl shadow-md border p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-times-circle text-red-500"></i>
                    Stok Habis
                </h2>
                <div class="space-y-2">
                    @forelse($stokHabis as $item)
                    <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-red-50 to-pink-50 rounded-lg border border-red-200 hover:shadow-md transition">
                        <div class="flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->cover_buku) }}" 
                                 alt="{{ $item->judul_buku }}"
                                 class="w-10 h-14 object-cover rounded shadow border-2 border-white opacity-75">
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm truncate" title="{{ $item->judul_buku }}">
                                {{ $item->judul_buku }}
                            </p>
                            <p class="text-xs text-gray-600">{{ $item->kategori->kategori }}</p>
                        </div>
                        <div class="flex-shrink-0 text-right">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-red-500 text-white">
                                <i class="fas fa-ban mr-1"></i> HABIS
                            </span>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-gray-500">
                        <i class="fas fa-smile-beam text-2xl mb-1 text-green-500"></i>
                        <p class="text-sm">Tidak ada stok habis!</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terbaru -->
    <div class="bg-white rounded-xl shadow-md border overflow-hidden mb-8">
        <div class="p-6 border-b bg-gray-50">
            <div class="flex items-center justify-between">
                <h2 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class="fas fa-receipt text-indigo-600"></i>
                    Transaksi Terbaru
                </h2>
                <a href="{{ route('owner.laporan.index') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                    Lihat Semua <i class="fas fa-arrow-right ml-1"></i>
                </a>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Waktu</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Metode</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($transaksiTerbaru as $transaksi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->kasir->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            <span class="font-medium">{{ $transaksi->items->count() }}</span> item
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                            Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaksi->metode_bayar == 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ strtoupper($transaksi->metode_bayar) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Grafik Pendapatan
    const ctx = document.getElementById('pendapatanChart');
    const grafikData = @json($grafikPendapatan);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: grafikData.map(item => item.tanggal),
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData.map(item => item.pendapatan),
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: 'rgb(79, 70, 229)',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endsection