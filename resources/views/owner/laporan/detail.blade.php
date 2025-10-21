@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600" aria-label="breadcrumb">
        <ol class="list-reset flex">
            <li><a href="{{ route('owner.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a></li>
            <li class="mx-2">/</li>
            <li><a href="{{ route('owner.laporan.index') }}" class="text-blue-600 hover:underline">Laporan Penjualan</a></li>
            <li class="mx-2">/</li>
            <li><span class="text-blue-600 hover:underline">Detail Transaksi</span></li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto px-6 py-10">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800 flex items-center gap-3">
            <i class="fas fa-list text-indigo-600"></i> 
            Detail Transaksi
            @if($transaksi->status === 'refund')
                <span class="px-4 py-2 text-sm font-bold bg-red-100 text-red-700 rounded-full">
                    <i class="fas fa-undo mr-1"></i> DI-REFUND
                </span>
            @endif
        </h1>
        <a href="{{ route('owner.laporan.index') }}" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition font-medium">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <!-- Alert Refund -->
    @if($transaksi->status === 'refund')
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-500 p-6 rounded-lg mb-6 shadow-md">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-lg font-bold text-red-900 mb-2">Transaksi Telah Di-Refund</h3>
                <div class="text-sm text-red-700 space-y-1">
                    <p><i class="fas fa-calendar-alt mr-2"></i><strong>Waktu Refund:</strong> {{ \Carbon\Carbon::parse($transaksi->refund_at)->format('d/m/Y H:i') }}</p>
                    @if($transaksi->refundBy)
                        <p><i class="fas fa-user-shield mr-2"></i><strong>Disetujui oleh:</strong> {{ $transaksi->refundBy->name }} ({{ ucfirst($transaksi->refundBy->role) }})</p>
                    @endif
                    <p class="mt-2"><i class="fas fa-info-circle mr-2"></i>Stok barang telah dikembalikan dan pembayaran telah di-refund.</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Info Transaksi -->
    <div class="bg-white p-6 rounded-xl shadow-md border mb-6 {{ $transaksi->status === 'refund' ? 'opacity-75' : '' }}">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div>
                <p class="text-sm text-gray-600 mb-1">ID Transaksi</p>
                <p class="font-bold text-gray-800">#{{ $transaksi->id }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Tanggal</p>
                <p class="font-bold text-gray-800">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Kasir</p>
                <p class="font-bold text-gray-800">{{ $transaksi->kasir->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Metode Bayar</p>
                <span class="px-3 py-1 text-xs font-semibold rounded-full 
                    {{ $transaksi->metode_bayar == 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ strtoupper($transaksi->metode_bayar) }}
                </span>
            </div>
            <div>
                <p class="text-sm text-gray-600 mb-1">Status</p>
                @if($transaksi->status === 'refund')
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-700 inline-flex items-center gap-1">
                        <i class="fas fa-undo"></i> REFUND
                    </span>
                @else
                    <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-700 inline-flex items-center gap-1">
                        <i class="fas fa-check-circle"></i> SELESAI
                    </span>
                @endif
            </div>
        </div>
    </div>

    <!-- Daftar Item -->
    <div class="bg-white rounded-xl shadow-md border overflow-hidden mb-6 {{ $transaksi->status === 'refund' ? 'opacity-75' : '' }}">
        <div class="p-6 border-b bg-gray-50">
            <h2 class="text-xl font-bold text-gray-800">
                <i class="fas fa-book text-indigo-600"></i> Item Buku</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Buku</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Harga</th>
                        <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Qty</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($transaksi->items as $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 text-sm">
                            <div class="flex items-center gap-3">
                                @if($item->buku->cover_buku)
                                <img src="{{ asset('storage/' . $item->buku->cover_buku) }}" 
                                     alt="Cover" class="w-12 h-16 object-cover rounded shadow">
                                @else
                                <div class="w-12 h-16 bg-gray-200 rounded shadow flex items-center justify-center">
                                    <i class="fas fa-book text-gray-400"></i>
                                </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-800">{{ $item->buku->judul_buku ?? '-' }}</p>
                                    <p class="text-xs text-gray-600">{{ $item->buku->pengarang ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-center font-semibold">
                            {{ $item->qty }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold text-gray-900">
                            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Ringkasan Pembayaran -->
    <div class="bg-white p-6 rounded-xl shadow-md border {{ $transaksi->status === 'refund' ? 'opacity-75' : '' }}">
        <h2 class="text-xl font-bold text-gray-800 mb-4">
            <i class="fas fa-money-bill text-indigo-600"></i> Ringkasan Pembayaran</h2>
        <div class="space-y-3">
            <div class="flex justify-between text-gray-700">
                <span>Total Harga:</span>
                <span class="font-semibold {{ $transaksi->status === 'refund' ? 'line-through text-gray-400' : '' }}">
                    Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between text-red-600">
                <span>Diskon:</span>
                <span class="font-semibold {{ $transaksi->status === 'refund' ? 'line-through text-gray-400' : '' }}">
                    - Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}
                </span>
            </div>
            <div class="border-t pt-3 flex justify-between text-lg font-bold {{ $transaksi->status === 'refund' ? 'text-gray-400 line-through' : 'text-green-600' }}">
                <span>Subtotal:</span>
                <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="border-t pt-3 flex justify-between text-gray-700">
                <span>Dibayar:</span>
                <span class="font-semibold {{ $transaksi->status === 'refund' ? 'line-through text-gray-400' : '' }}">
                    Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}
                </span>
            </div>
            <div class="flex justify-between text-blue-600">
                <span>Kembalian:</span>
                <span class="font-semibold {{ $transaksi->status === 'refund' ? 'line-through text-gray-400' : '' }}">
                    Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}
                </span>
            </div>
            
            @if($transaksi->status === 'refund')
            <div class="border-t-2 border-red-300 pt-3 mt-3">
                <div class="bg-red-50 p-4 rounded-lg">
                    <div class="flex justify-between text-lg font-bold text-red-600">
                        <span><i class="fas fa-undo mr-2"></i>Total Di-Refund:</span>
                        <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection