<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PembayaranController extends Controller
{
    public function bayar(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('MIDTRANS_SERVER_KEY');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');

        // Contoh: ambil total dari request/cart
        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->total, // contoh: 50000
            ],
            'customer_details' => [
                'first_name' => 'Kasir Sekolah',
                'email' => 'kasir@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['token' => $snapToken]);
    }
}