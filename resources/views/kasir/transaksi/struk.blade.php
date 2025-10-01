@extends('layouts.app')

@section('content')
    <style>
        @media print {
            /* Sembunyikan semua elemen selain #print-area */
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

            /* Hilangkan tombol cetak */
            .no-print {
                display: none !important;
            }

            /* Hilangkan sidebar & navbar */
            aside, header {
                display: none !important;
            }
        }
    </style>

    <div id="print-area" class="max-w-md mx-auto bg-white p-6 shadow">
        <h2 class="text-center text-lg font-bold">Buku Kita</h2>
        <p class="text-center">Jl. Jend. Sudirman No. 123</p>
        <p class="text-center">Kasir: {{ $transaksi->kasir->name }}</p>
        <hr class="my-2">

        <table class="w-full text-sm">
            @foreach ($transaksi->items as $item)
                <tr>
                    <td>{{ $item->buku->judul_buku }}</td>
                    <td>x{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>
        <hr class="my-2">

        <p>Total: Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</p>
        <p>Diskon: Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</p>
        <p>Subtotal: Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</p>
        <p>Dibayar: Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}</p>
        <p>Kembalian: Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</p>
        <p>Metode Bayar: {{ strtoupper($transaksi->metode_bayar) }}</p>
        <hr class="my-2">

        <p class="text-center">Terima kasih telah berbelanja!</p>
    </div>

    <div class="flex justify-center mt-4 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            Cetak Struk
        </button>
    </div>
@endsection