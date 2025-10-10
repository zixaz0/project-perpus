@extends('layouts.app')

@section('content')
<div class="p-6 w-full">

    <!-- Judul -->
    <h1 class="text-2xl font-bold text-gray-800 mb-4">Selamat Datang, {{ Auth::user()->username }} 👋</h1>
    <p class="text-gray-600 mb-6">Berikut ringkasan aktivitas kasir hari ini.</p>

    <!-- Ringkasan Statistik -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white shadow-lg rounded-2xl p-4 border border-gray-100">
            <h2 class="text-gray-500 text-sm">Transaksi Hari Ini</h2>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $transaksiHariIni ?? 0 }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-4 border border-gray-100">
            <h2 class="text-gray-500 text-sm">Pendapatan Hari Ini</h2>
            <p class="text-3xl font-bold text-green-600 mt-2">Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-4 border border-gray-100">
            <h2 class="text-gray-500 text-sm">Buku Terjual</h2>
            <p class="text-3xl font-bold text-indigo-600 mt-2">{{ $bukuTerjual ?? 0 }}</p>
        </div>
        <div class="bg-white shadow-lg rounded-2xl p-4 border border-gray-100">
            <h2 class="text-gray-500 text-sm">Total Buku</h2>
            <p class="text-3xl font-bold text-orange-600 mt-2">{{ $totalBuku ?? 0 }}</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-8">
        <a href="{{ route('kasir.buku.index') ?? '#' }}" class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white shadow-lg rounded-2xl p-6 border border-blue-600 transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Transaksi Baru</h3>
                    <p class="text-sm text-blue-100 mt-1">Mulai transaksi kasir</p>
                </div>
                <svg class="w-12 h-12 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
        </a>
        
        <a href="{{ route('kasir.buku.index') ?? '#' }}" class="bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white shadow-lg rounded-2xl p-6 border border-purple-600 transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Cek Stok Buku</h3>
                    <p class="text-sm text-purple-100 mt-1">Lihat ketersediaan buku</p>
                </div>
                <svg class="w-12 h-12 text-purple-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </a>
        
        <a href="{{ route('kasir.riwayat_transaksi.index') ?? '#' }}" class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white shadow-lg rounded-2xl p-6 border border-green-600 transition-all transform hover:scale-105">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold">Riwayat Transaksi</h3>
                    <p class="text-sm text-green-100 mt-1">Lihat semua transaksi</p>
                </div>
                <svg class="w-12 h-12 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
            </div>
        </a>
    </div>

    <!-- Row dengan Grafik dan Stok Menipis -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-8">
        <!-- Grafik Penjualan (2 kolom) -->
        <div class="lg:col-span-2 bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Grafik Penjualan Mingguan</h3>
            <canvas id="chartPenjualan" height="90"></canvas>
        </div>

        <!-- Stok Menipis (1 kolom) -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Stok Menipis</h3>
                <span class="bg-red-100 text-red-600 text-xs font-semibold px-2 py-1 rounded-full">
                    {{ count($stokMenipis ?? []) }}
                </span>
            </div>
            <div class="space-y-3 max-h-64 overflow-y-auto">
                @forelse ($stokMenipis ?? [] as $buku)
                    <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg border border-red-100">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-800 truncate">{{ $buku->judul ?? 'Buku' }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ $buku->kategori->nama ?? '-' }}</p>
                        </div>
                        <span class="ml-2 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                            {{ $buku->stok ?? 0 }}
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm text-gray-500">Semua stok aman!</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Buku Terlaris & Transaksi Terbaru -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
        <!-- Buku Terlaris -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Buku Terlaris Minggu Ini</h3>
                <svg class="w-5 h-5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                </svg>
            </div>
            <div class="space-y-3 max-h-80 overflow-y-auto">
                @forelse ($bukuTerlaris ?? [] as $index => $buku)
                    <div class="flex items-center p-3 bg-gray-50 rounded-lg border border-gray-100 hover:bg-gray-100 transition">
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                            {{ $index + 1 }}
                        </div>
                        <div class="ml-4 flex-1">
                            <p class="text-sm font-semibold text-gray-800">{{ $buku->judul ?? 'Judul Buku' }}</p>
                            <p class="text-xs text-gray-500">{{ $buku->penulis ?? 'Penulis' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-bold text-blue-600">{{ $buku->total_terjual ?? 0 }}</p>
                            <p class="text-xs text-gray-500">terjual</p>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                        <p class="text-sm text-gray-500">Belum ada data penjualan</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Transaksi Terbaru -->
        <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-100">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-700">Transaksi Terbaru</h3>
                <a href="{{ route('kasir.transaksi.index') }}" class="text-blue-500 hover:underline text-sm">Lihat Semua</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-600">
                    <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                        <tr>
                            <th class="px-4 py-2">#</th>
                            <th class="px-4 py-2">Kode Transaksi</th>
                            <th class="px-4 py-2">Tanggal</th>
                            <th class="px-4 py-2">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($transaksiTerbaru ?? [] as $t)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2">{{ $loop->iteration }}</td>
                                <td class="px-4 py-2 font-medium text-gray-800">{{ $t->kode_transaksi }}</td>
                                <td class="px-4 py-2">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2 text-green-600 font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-8 text-gray-500">
                                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
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
            backgroundColor: '#2563eb',
            borderRadius: 6,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: { display: false }
        },
        scales: {
            y: { beginAtZero: true, ticks: { stepSize: 2 } }
        }
    }
});
</script>
@endsection