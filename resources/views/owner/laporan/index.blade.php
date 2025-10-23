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
            <a href="{{ route('owner.laporan.print', ['tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir, 'status' => request('status')]) }}" 
           target="_blank"
           class="px-5 py-2.5 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md transition font-medium">
            <i class="fas fa-print"></i> Cetak Laporan
        </a>
    </div>

    <!-- Filter Periode & Status -->
    <div class="bg-white p-6 rounded-xl shadow-md border mb-6">
        <form method="GET" action="{{ route('owner.laporan.index') }}" class="space-y-4">
            <!-- Filter Tanggal -->
            <div class="flex flex-wrap gap-4 items-end">
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
            </div>

            <!-- Filter Status -->
            <div class="border-t pt-4">
                <label class="block text-sm font-medium text-gray-700 mb-3">Filter Status Transaksi</label>
                <div class="flex flex-wrap gap-3">
                    <button type="submit" name="status" value="" 
                            class="cursor-pointer px-4 py-2 rounded-lg font-medium transition {{ request('status') === null ? 'bg-indigo-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-list"></i> Semua Transaksi
                    </button>
                    <button type="submit" name="status" value="selesai" 
                            class="cursor-pointer px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'selesai' ? 'bg-green-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-check-circle"></i> Hanya Selesai
                    </button>
                    <button type="submit" name="status" value="refund" 
                            class="cursor-pointer px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'refund' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        <i class="fas fa-undo"></i> Hanya Refund
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Transaksi</p>
                    <h3 class="text-3xl font-bold">{{ number_format($total_transaksi) }}</h3>
                    <p class="text-xs opacity-75 mt-1">Berhasil</p>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-receipt text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75 mt-1">Setelah refund</p>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-money-bill-wave text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-500 to-orange-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Total Diskon</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($total_diskon, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75 mt-1">Diberikan</p>
                </div>
                <div class="bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-tag text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-6 rounded-xl shadow-lg transform hover:scale-105 transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Buku Terjual</p>
                    <h3 class="text-3xl font-bold">{{ number_format($total_buku_terjual) }}</h3>
                    <p class="text-xs opacity-75 mt-1">Unit</p>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-book text-3xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Card Tambahan: Transaksi Refund -->
    @if($total_refund > 0)
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-red-500 to-red-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Transaksi Di-Refund</p>
                    <h3 class="text-3xl font-bold">{{ number_format($total_refund) }}</h3>
                    <p class="text-xs opacity-75 mt-1">Transaksi</p>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-undo text-3xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-pink-500 to-pink-600 text-white p-6 rounded-xl shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm opacity-90 mb-1">Nilai Refund</p>
                    <h3 class="text-2xl font-bold">Rp {{ number_format($total_nilai_refund, 0, ',', '.') }}</h3>
                    <p class="text-xs opacity-75 mt-1">Total dikembalikan</p>
                </div>
                <div class=" bg-opacity-20 p-3 rounded-lg">
                    <i class="fas fa-hand-holding-usd text-3xl"></i>
                </div>
            </div>
        </div>
    </div>
    @endif
    <!-- Tabel Transaksi -->
    <div class="bg-white rounded-xl shadow-md border overflow-hidden">
        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-clipboard text-indigo-600"></i> Riwayat Transaksi
                @if(request('status') === 'selesai')
                    <span class="text-green-600">(Selesai)</span>
                @elseif(request('status') === 'refund')
                    <span class="text-red-600">(Refund)</span>
                @else
                    <span class="text-indigo-600">(Semua)</span>
                @endif
            </h2>
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
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksis as $transaksi)
                    <tr class="hover:bg-gray-50 {{ $transaksi->status === 'refund' ? 'bg-red-50 opacity-75' : '' }}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $loop->iteration + ($transaksis->currentPage() - 1) * $transaksis->perPage() }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->created_at->format('d/m/Y H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $transaksi->kasir->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold {{ $transaksi->status === 'refund' ? 'text-gray-400 line-through' : 'text-gray-900' }}">
                            Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm {{ $transaksi->status === 'refund' ? 'text-gray-400 line-through' : 'text-red-600' }}">
                            Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold {{ $transaksi->status === 'refund' ? 'text-gray-400 line-through' : 'text-green-600' }}">
                            Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $transaksi->metode_bayar == 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ strtoupper($transaksi->metode_bayar) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($transaksi->status === 'refund')
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 flex items-center justify-center gap-1 w-fit">
                                    <i class="fas fa-undo"></i> REFUND
                                </span>
                            @else
                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 flex items-center justify-center gap-1 w-fit">
                                    <i class="fas fa-check-circle"></i> SELESAI
                                </span>
                            @endif
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
                        <td colspan="3" class="px-6 py-4 text-right">
                            @if(request('status') === 'refund')
                                TOTAL REFUND:
                            @elseif(request('status') === 'selesai')
                                TOTAL SELESAI:
                            @else
                                GRAND TOTAL (Berhasil):
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm">
                            Rp {{ number_format($transaksis->sum('total_harga'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-red-600">
                            Rp {{ number_format($transaksis->sum('diskon'), 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-green-600">
                            Rp {{ number_format($transaksis->sum('subtotal'), 0, ',', '.') }}
                        </td>
                        <td colspan="3"></td>
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
            <p class="text-gray-500 text-lg">
                Tidak ada transaksi 
                @if(request('status') === 'selesai')
                    dengan status <strong>Selesai</strong>
                @elseif(request('status') === 'refund')
                    dengan status <strong>Refund</strong>
                @endif
                pada periode ini
            </p>
        </div>
        @endif
    </div>
</div>
@endsection