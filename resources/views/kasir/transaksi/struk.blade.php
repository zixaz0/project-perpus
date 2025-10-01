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

        /* Styling struk */
        #print-area {
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.4;
        }
        #print-area h2 {
            font-size: 18px;
            letter-spacing: 1px;
        }
        .divider {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .totals p {
            display: flex;
            justify-content: space-between;
        }
    </style>

    <div id="print-area" class="max-w-sm mx-auto bg-white p-6 shadow">
        <!-- Header -->
        <div class="text-center mb-2">
            <h2 class="font-bold">Buku Kita</h2>
            <p>Jl. Jend. Sudirman No. 123</p>
            <p>Kasir: {{ $transaksi->kasir->name }}</p>
            <p>{{ now()->format('d/m/Y H:i') }}</p>
        </div>

        <div class="divider"></div>

        <!-- Items -->
        <table class="w-full text-sm">
            @foreach ($transaksi->items as $item)
                <tr>
                    <td colspan="3">{{ $item->buku->judul_buku }}</td>
                </tr>
                <tr>
                    <td class="pl-2">x{{ $item->qty }}</td>
                    <td class="text-right">Rp {{ number_format($item->harga_satuan, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <!-- Totals -->
        <div class="totals text-sm">
            <p><span>Total</span> <span>Rp {{ number_format($transaksi->total_harga, 0, ',', '.') }}</span></p>
            <p><span>Diskon</span> <span>- Rp {{ number_format($transaksi->diskon, 0, ',', '.') }}</span></p>
            <p><span>Subtotal</span> <span>Rp {{ number_format($transaksi->subtotal, 0, ',', '.') }}</span></p>
            <p><span>Dibayar</span> <span>Rp {{ number_format($transaksi->dibayar, 0, ',', '.') }}</span></p>
            <p><span>Kembalian</span> <span>Rp {{ number_format($transaksi->kembalian, 0, ',', '.') }}</span></p>
            <p><span>Metode</span> <span>{{ strtoupper($transaksi->metode_bayar) }}</span></p>
        </div>

        <div class="divider"></div>

        <!-- Footer -->
        <p class="text-center mt-2">~~ Terima kasih ~~</p>
        <p class="text-center">Barang yang sudah dibeli <br> tidak dapat dikembalikan</p>
    </div>

    <div class="flex justify-center mt-4 no-print">
        <button onclick="window.print()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded">
            <i class="fas fa-print mr-2 bg-blue-600"></i> Cetak Struk
        </button>
    </div>
@endsection
