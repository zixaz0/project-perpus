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

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        table thead {
            background-color: #4F46E5;
            color: white;
        }

        table th, table td {
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

        @media print {
            .no-print {
                display: none !important;
            }

            body {
                background: white;
            }

            table {
                box-shadow: none;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Laporan Penjualan</h1>
        <p>Periode: {{ \Carbon\Carbon::parse($tanggal_awal)->format('d M Y') }} - {{ \Carbon\Carbon::parse($tanggal_akhir)->format('d M Y') }}</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <h3>Total Pendapatan</h3>
            <p>Rp {{ number_format($total_pendapatan, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Diskon</h3>
            <p>Rp {{ number_format($total_diskon, 0, ',', '.') }}</p>
        </div>
        <div class="stat-card">
            <h3>Total Buku Terjual</h3>
            <p>{{ $total_buku_terjual }}</p>
        </div>
    </div>

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
            </tr>
        </thead>
        <tbody>
            @forelse ($transaksis as $index => $t)
                <tr>
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
                    <td>Rp {{ number_format($t->subtotal, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($t->diskon, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($t->subtotal - $t->diskon, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" style="text-align: center;">Tidak ada data transaksi</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Dicetak oleh sistem pada {{ now()->format('d/m/Y H:i') }}</p>
        <p class="print-time">Terima kasih telah menggunakan sistem laporan penjualan kami.</p>
    </div>

    <button onclick="window.print()" class="print-btn no-print">
        <i class="fas fa-print"></i> Cetak Laporan</button>
    <button onclick="window.close()" class="no-print" style="padding: 10px 20px; background: #f44336; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
    <i class="fas fa-times"></i> Tutup
    </button>

</body>
</html>