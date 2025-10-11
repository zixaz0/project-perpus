@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('owner.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a></li>
            <li class="mx-2">/</li>
            <li><a href="{{ route('owner.laporan.index') }}" class="text-blue-600 hover:underline">Laporan Penjualan</a></li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-7xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            <i class="fas fa-chart-bar text-indigo-600"></i> Laporan Penjualan</h1>
            <a href="{{ route('owner.laporan.print', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]) }}" 
           target="_blank"
           class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition font-medium">
            <i class="fas fa-print"></i> Cetak Laporan
        </a>
    </div>

    <!-- Filter Periode -->
    <div class="bg-white p-6 rounded-xl shadow-md border mb-6">
        <form method="GET" action="{{ route('owner.laporan.index') }}" class="flex flex-wrap gap-4 items-end">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Awal</label>
                <input type="date" name="tanggal_awal" value="{{ $tanggal_awal }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Akhir</label>
                <input type="date" name="tanggal_akhir" value="{{ $tanggal_akhir }}" 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="cursor-pointer px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-md transition font-medium">
                <i class="fas fa-filter"></i> Filter
            </button>
            <a href="{{ route('owner.laporan.index') }}" class="px-6 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
                <i class="fas fa-redo"></i> Reset
            </a>
        </form>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold">{{ number_format($total_transaksi) }}</h3>
                </div>
                <div class= bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Pendapatan</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                </div>
                <div class="bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Diskon</p>
                    <h3 class="text-3xl font-bold">Rp {{ number_format($total_diskon, 0, ',', '.') }}</h3>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-tag text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Buku Terjual</p>
                    <h3 class="text-3xl font-bold">{{ number_format($total_buku_terjual) }}</h3>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-book text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Buku Terlaris -->
    @if($buku_terlaris->count() > 0)
    <div class="bg-white p-6 rounded-xl shadow-md border mb-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-fire text-indigo-600"></i> Top 5 Buku Terlaris</h2>
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            @foreach($buku_terlaris as $index => $item)
            <div class="bg-gradient-to-br from-indigo-50 to-purple-50 p-4 rounded-lg border border-indigo-200">
                <div class="flex items-start gap-3">
                    <div class="bg-indigo-600 text-white w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm flex-shrink-0">
                        {{ $index + 1 }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate" title="{{ $item->buku->judul_buku ?? '-' }}">
                            {{ $item->buku->judul_buku ?? '-' }}
                        </p>
                        <p class="text-xs text-gray-600 mt-1">Terjual: <span class="font-bold text-indigo-600">{{ $item->total_terjual }}</span> buku</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-xl shadow-md border overflow-hidden">
        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clipboard text-indigo-600"></i> Riwayat Transaksi</h2>
            <p class="text-sm text-gray-600 mt-1">
                Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d/m/Y') }}
            </p>
        </div>

        @if($transaksis->count() > 0)
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Diskon</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Metode</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->kasir->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}
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
                        <td class="px-6 py-4 whitespace-nowrap text-center">
                            <a href="{{ route('owner.laporan.detail', $transaksi->id) }}" 
                               class="text-blue-600 hover:text-blue-800 font-medium text-sm">
                                <i class="fas fa-eye"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-100">
                    <tr class="font-bold">
                        <td colspan="3" class="px-6 py-4 text-right">GRAND TOTAL:</td>
                        <td class="px-6 py-4 text-sm">Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-red-600">Rp {{ number_format($transaksis->sum('diskon'), 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-sm text-green-600">Rp {{ number_format($transaksis->sum('subtotal'), 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t">
            {{ $transaksis->appends(request()->query())->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <div class="inline-block bg-gray-100 p-6 rounded-full mb-4">
                <i class="fas fa-inbox text-4xl text-gray-400"></i>
            </div>
            <p class="text-gray-500 text-lg">Tidak ada transaksi pada periode ini</p>
        </div>
        @endif
    </div>
</div>
@endsection