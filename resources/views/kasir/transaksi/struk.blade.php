@extends('layouts.app')

@section('content')
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #print-area, #print-area * {
                visibility: visible;
            }
            #print-area {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print, aside, header {
                display: none !important;
            }
        }
    </style>

    <div id="print-area" class="bg-white p-4 shadow max-w-xs mx-auto font-mono text-sm leading-snug">
        <!-- Header -->
        <div class="text-center mb-3">
            <h2 class="text-lg font-bold tracking-wide">Buku Kita</h2>
            <p class="text-xs">Jl. Jend. Sudirman No. 123</p>
            <p class="text-xs">Kasir: {{ $transaksi->kasir->name }}</p>
            <p class="text-xs">{{ $transaksi->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <div class="border-t border-dashed border-gray-800 my-2"></div>

        <!-- Items -->
        @foreach ($transaksi->items as $item)
            <div>
                <p class="font-semibold">{{ $item->buku->judul_buku }}</p>
                <div class="flex justify-between text-xs">
                    <span>x{{ $item->qty }} @ Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach

        <div class="border-t border-dashed border-gray-800 my-2"></div>

        <!-- Totals -->
        <div class="space-y-1 text-xs">
            <div class="flex justify-between">
                <span>Total</span>
                <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Diskon</span>
                <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-semibold">
                <span>Subtotal</span>
                <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Dibayar</span>
                <span>Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Kembalian</span>
                <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>Metode</span>
                <span>{{ strtoupper($transaksi->metode_bayar) }}</span>
            </div>
        </div>

        <div class="border-t border-dashed border-gray-800 my-2"></div>

        <!-- Footer -->
        <div class="text-center mt-3 text-xs">
            <p>~~ Terima kasih ~~</p>
            <p class="italic">Barang yang sudah dibeli<br>tidak dapat dikembalikan</p>
        </div>
    </div>

    <div class="flex justify-center gap-3 mt-4 no-print">
        <!-- Tombol Cetak -->
        <button onclick="window.print()" 
            class="cursor-pointer bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded flex items-center gap-2">
            <i class="fas fa-print"></i> Cetak Struk
        </button>

        <!-- Tombol Kembali -->
        <a href="{{ route('kasir.buku.index') }}" 
            class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded flex items-center gap-2">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@endsection