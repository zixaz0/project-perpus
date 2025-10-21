<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: "Poppins", Arial, sans-serif;
            padding: 30px;
            background: #f9fafb;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 4px solid #4F46E5;
        }

        .header h1 {
            font-size: 26px;
            color: #1E1B4B;
            letter-spacing: 1px;
            margin-bottom: 5px;
            text-transform: uppercase;
        }

        .header p {
            font-size: 14px;
            color: #6B7280;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin: 25px 0;
        }

        .stat-card {
            background: #EEF2FF;
            border: 1px solid #C7D2FE;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(79, 70, 229, 0.1);
            transition: transform 0.2s ease;
        }

        .stat-card.refund {
            background: #FEE2E2;
            border: 1px solid #FCA5A5;
        }

        .stat-card.refund h3 {
            color: #DC2626;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card h3 {
            font-size: 14px;
            color: #4F46E5;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .stat-card p {
            font-size: 16px;
            font-weight: bold;
            color: #111827;
        }

        .stats-refund {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
            margin: 15px 0 25px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        table thead {
            background-color: #4F46E5;
            color: white;
        }

        table th,
        table td {
            border: 1px solid #E5E7EB;
            padding: 8px 10px;
            text-align: left;
            vertical-align: top;
        }

        table th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.5px;
        }

        table tbody tr:nth-child(even) {
            background: #F9FAFB;
        }

        table tbody tr:hover {
            background: #EEF2FF;
        }

        table tbody tr.refund-row {
            background: #FEE2E2;
            opacity: 0.7;
        }

        table tbody tr.refund-row:hover {
            background: #FECACA;
        }

        table tbody tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-berhasil {
            background: #D1FAE5;
            color: #065F46;
        }

        .status-refund {
            background: #FEE2E2;
            color: #991B1B;
        }

        .line-through {
            text-decoration: line-through;
            color: #9CA3AF;
        }

        /* Total summary section - dipisah dari table */
        .total-summary {
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .total-row {
            display: grid;
            grid-template-columns: 5fr 1fr 1fr 1fr 1fr;
            padding: 12px 10px;
            border: 1px solid #E5E7EB;
            font-weight: bold;
            font-size: 12px;
        }

        .total-row.success {
            background: #D1FAE5;
            color: #065F46;
        }

        .total-row.refund {
            background: #FEE2E2;
            color: #991B1B;
        }

        .total-row > div:first-child {
            text-align: right;
            padding-right: 10px;
        }

        .footer {
            text-align: right;
            margin-top: 40px;
            font-size: 12px;
            color: #555;
        }

        .footer .print-time {
            font-style: italic;
            color: #6B7280;
        }

        .print-btn {
            display: inline-block;
            margin-top: 25px;
            background: #4F46E5;
            color: white;
            border: none;
            border-radius: 6px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.2s ease;
        }

        .print-btn:hover {
            background: #3730A3;
        }

        .close-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
            margin-left: 10px;
        }

        .close-btn:hover {
            background: #d32f2f;
        }

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
                padding: 20px;
            }

            table {
                box-shadow: none;
            }

            table tbody tr {
                break-inside: avoid;
                page-break-inside: avoid;
            }

            .total-summary {
                page-break-before: auto;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
        }

        @page {
            margin: 20mm;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} -
            {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    </div>

    <div class="stats no-print">
        <div class="stat-card">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
            <small style="color: #6B7280;">Setelah Refund</small>
        </div>
        <div class="stat-card">
            <h3>Total Diskon</h3>
            <p>Rp {{ number_format($total_diskon, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Buku Terjual</h3>
            <p>{{ $total_buku_terjual }}</p>
            <small style="color: #6B7280;">Unit</small>
        </div>
    </div>

    @if (isset($total_refund) && $total_refund > 0)
        <div class="stats-refund no-print">
            <div class="stat-card refund">
                <h3>Transaksi Di-Refund</h3>
                <p>{{ $total_refund }}</p>
                <small style="color: #991B1B;">Transaksi</small>
            </div>
            <div class="stat-card refund">
                <h3>Nilai Refund</h3>
                <p>Rp {{ number_format($total_nilai_refund, 0, ',', '.') }}</p>
                <small style="color: #991B1B;">Dikembalikan</small>
            </div>
        </div>
    @endif

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Kasir</th>
                <th>Daftar Buku</th>
                <th>Qty</th>
                <th>Subtotal</th>
                <th>Diskon</th>
                <th>Total Akhir</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalBerhasil = 0;
                $totalDiskonBerhasil = 0;
                $totalRefundValue = 0;
            @endphp

            @forelse ($transaksis as $index => $t)
                @php
                    $isRefund = $t->status === 'refund';
                    if (!$isRefund) {
                        $totalBerhasil += $t->subtotal;
                        $totalDiskonBerhasil += $t->diskon;
                    } else {
                        $totalRefundValue += $t->subtotal;
                    }
                @endphp
                <tr class="{{ $isRefund ? 'refund-row' : '' }}">
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y H:i') }}</td>
                    <td>{{ $t->kasir->name ?? '-' }}</td>
                    <td>
                        @foreach ($t->items as $item)
                            • {{ $item->buku->judul_buku ?? 'Tidak Diketahui' }} <br>
                        @endforeach
                    </td>
                    <td>
                        @foreach ($t->items as $item)
                            {{ $item->qty }} <br>
                        @endforeach
                    </td>
                    <td class="{{ $isRefund ? 'line-through' : '' }}">
                        Rp {{ number_format($t->subtotal, 0, ',', '.') }}
                    </td>
                    <td class="{{ $isRefund ? 'line-through' : '' }}">
                        Rp {{ number_format($t->diskon, 0, ',', '.') }}
                    </td>
                    <td class="{{ $isRefund ? 'line-through' : '' }}">
                        Rp {{ number_format($t->subtotal - $t->diskon, 0, ',', '.') }}
                    </td>
                    <td>
                        @if ($isRefund)
                            <span class="status-badge status-refund">
                                <i class="fas fa-undo"></i> Refund</span>
                        @else
                            <span class="status-badge status-berhasil">
                                <i class="fas fa-check-circle"></i> Berhasil</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" style="text-align: center;">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Total summary di luar table -->
    <div class="total-summary">
        <div class="total-row success">
            <div>TOTAL BERHASIL:</div>
            <div>Rp {{ number_format($totalBerhasil, 0, ',', '.') }}</div>
            <div>Rp {{ number_format($totalDiskonBerhasil, 0, ',', '.') }}</div>
            <div>Rp {{ number_format($totalBerhasil - $totalDiskonBerhasil, 0, ',', '.') }}</div>
            <div></div>
        </div>
        @if ($totalRefundValue > 0)
        <div class="total-row refund">
            <div>TOTAL REFUND:</div>
            <div colspan="3">Rp {{ number_format($totalRefundValue, 0, ',', '.') }}</div>
            <div></div>
            <div></div>
            <div></div>
        </div>
        @endif
    </div>

    <div class="footer">
        <p><strong>Dicetak oleh sistem pada {{ now()->format('d/m/Y H:i') }}</strong></p>
        <p class="print-time">Terima kasih telah menggunakan sistem laporan penjualan kami.</p>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="print-btn">
            <i class="fas fa-print"></i> Cetak Laporan
        </button>
        <button onclick="window.close()" class="close-btn">
            <i class="fas fa-times"></i> Tutup
        </button>
    </div>

</body>

</html>