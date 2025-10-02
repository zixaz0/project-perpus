@extends('layouts.app')

@section('breadcrumb')
    <nav class="text-sm text-gray-600 mb-4" aria-label="breadcrumb">
        <ol class="list-reset flex items-center space-x-2">
            <li>
                <a href="{{ route('kasir.dashboard') }}" class="text-blue-600 hover:underline">Dashboard</a>
            </li>
            <li>/</li>
            <li class="text-gray-700">Riwayat Transaksi</li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="container mx-auto px-4">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
        <i class="fas fa-history text-indigo-600"></i> Riwayat Transaksi
    </h1>

    @forelse($transaksis as $t)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-xl transition mb-8 overflow-hidden border-l-4 border-indigo-500">
            <!-- Header -->
            <div class="px-6 py-4 border-b bg-gradient-to-r from-indigo-50 to-white flex justify-between items-center">
                <div>
                    <p class="text-xs text-gray-500">ID Transaksi</p>
                    <p class="font-bold text-gray-800">#{{ $t->id }}</p>
                    <p class="text-sm text-gray-600 mt-1">Kasir: <span class="font-medium">{{ $t->kasir->name ?? '-' }}</span></p>
                    <p class="text-sm text-gray-600">Tanggal: {{ $t->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-extrabold text-indigo-600">
                        Rp {{ number_format($t->total_harga, 0, ',', '.') }}
                    </p>
                    <a href="{{ route('kasir.transaksi.struk', $t->id) }}" 
                       class="inline-flex items-center gap-2 mt-3 px-4 py-2 text-sm bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md">
                        <i class="fas fa-receipt"></i> Lihat Struk
                    </a>
                </div>
            </div>

            <!-- Items -->
            <div class="overflow-x-auto">
                <table class="min-w-full border-collapse">
                    <thead>
                        <tr class="bg-indigo-100 text-indigo-700 text-sm uppercase">
                            <th class="px-5 py-3 text-left">Buku</th>
                            <th class="px-5 py-3 text-center">Qty</th>
                            <th class="px-5 py-3 text-right">Harga</th>
                            <th class="px-5 py-3 text-right">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-200">
                        @foreach ($t->items as $item)
                            <tr class="hover:bg-gray-50">
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
        {{ $transaksis->links() }}
    </div>
</div>
@endsection