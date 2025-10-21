@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600 mb-4" aria-label="breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li>/</li>
            <a href="" class="text-blue-600 hover:underline">Riwayat Transaksi</a>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-history text-indigo-600"></i> Riwayat Transaksi
    </h1>

    @forelse($transaksi as $t)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition mb-8 overflow-hidden border-l-4 {{ $t->status === 'refund' ? 'border-red-500' : 'border-indigo-500' }}">
            <!-- Header -->
            <div class="px-6 py-4 border-b {{ $t->status === 'refund' ? 'bg-gradient-to-r from-red-50 to-white' : 'bg-gradient-to-r from-indigo-50 to-white' }} flex justify-between items-center">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-xs text-gray-500">ID Transaksi</p>
                        @if($t->status === 'refund')
                            <span class="px-2 py-1 text-xs font-bold bg-red-100 text-red-700 rounded-full">REFUND</span>
                        @else
                            <span class="px-2 py-1 text-xs font-bold bg-green-100 text-green-700 rounded-full">SELESAI</span>
                        @endif
                    </div>
                    <p class="font-bold text-gray-800">#{{ $t->id }}</p>
                    <p class="text-sm text-gray-600 mt-1">Kasir: <span class="font-medium">{{ $t->kasir->name ?? '-' }}</span></p>
                    <p class="text-sm text-gray-600">Tanggal: {{ $t->created_at->format('d M Y, H:i') }}</p>
                    
                    @if($t->status === 'refund' && $t->refund_at)
                        <p class="text-xs text-red-600 mt-1">
                            <i class="fas fa-undo"></i> Refund pada {{ \Carbon\Carbon::parse($t->refund_at)->format('d M Y, H:i') }}
                            @if($t->refundBy)
                                oleh {{ $t->refundBy->name }}
                            @endif
                        </p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-2xl font-extrabold {{ $t->status === 'refund' ? 'text-red-600 line-through' : 'text-indigo-600' }}">
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </p>
                    @if($t->status === 'refund')
                        <p class="text-xs text-red-600 mt-1">
                            <i class="fas fa-ban"></i> Transaksi Dibatalkan
                        </p>
                    @endif
                </div>
            </div>

            <!-- Items -->
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="{{ $t->status === 'refund' ? 'bg-red-100 text-red-700' : 'bg-indigo-100 text-indigo-700' }} text-sm uppercase">
                            <th class="px-5 py-3 text-left">Buku</th>
                            <th class="px-5 py-3 text-center">Qty</th>
                            <th class="px-5 py-3 text-right">Harga</th>
                            <th class="px-5 py-3 text-right">total</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @foreach ($t->items as $item)
                            <tr class="hover:bg-gray-50 {{ $t->status === 'refund' ? 'opacity-60' : '' }}">
                                <td class="px-5 py-3">{{ $item->buku->judul_buku }}</td>
                                <td class="px-5 py-3 text-center">{{ $item->qty }}</td>
                                <td class="px-5 py-3 text-right">
                                    Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3 text-right font-semibold text-gray-900">
                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @empty
        <div class="text-center py-16">
            <i class="fas fa-box-open text-6xl text-gray-300 mb-4"></i>
            <p class="text-gray-500 text-lg font-medium">Belum ada transaksi.</p>
        </div>
    @endforelse

    <div class="mt-8">
        {{ $transaksi->links() }}
    </div>
</div>
@endsection