<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transaksi;
use App\Models\TransaksiItem;

class TransaksiSeeder extends Seeder
{
    public function run(): void
    {
        $transaksi = Transaksi::create([
            'kasir_id' => 3, // asumsi user kasir id = 1
            'total_harga' => 200000,
            'diskon' => 20000,
            'subtotal' => 180000,
            'dibayar' => 200000,
            'kembalian' => 20000,
            'metode_bayar' => 'cash',
        ]);

        TransaksiItem::create([
            'transaksi_id' => $transaksi->id,
            'buku_id' => 23, // contoh buku id 1
            'qty' => 2,
            'harga_satuan' => 100000,
            'subtotal' => 200000,
        ]);
    }
}
