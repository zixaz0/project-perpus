<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Transaksi;
use Midtrans\Config;
use Midtrans\Snap;

class KasirMidtransController extends Controller
{
    public function getToken(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'ORDER-' . uniqid();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $request->total,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json([
            'token' => $snapToken,
            'order_id' => $orderId,
        ]);
    }

    public function denyPayment(Request $request)
    {
        $orderId = $request->order_id;
        $transaksi = Transaksi::where('order_id', $orderId)->first();

        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        $transaksi->status = 'deny';

        foreach ($transaksi->detailTransaksi as $detail) {
            $buku = Buku::find($detail->buku_id);
            if ($buku && $buku->stokHarga) {
                $buku->stokHarga->stok += $detail->jumlah;
                $buku->stokHarga->save();
            }
        }

        $transaksi->save();

        return response()->json(['message' => 'Pembayaran ditolak dan stok dikembalikan.']);
    }

    public function handleNotification(Request $request)
    {
        $notif = $request->all();
        $transactionStatus = $notif['transaction_status'] ?? '';
        $orderId = $notif['order_id'] ?? '';

        $transaksi = Transaksi::where('order_id', $orderId)->first();
        if (!$transaksi) {
            return response()->json(['message' => 'Transaksi tidak ditemukan'], 404);
        }

        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $transaksi->status = 'success';
                break;

            case 'pending':
                $transaksi->status = 'pending';
                break;

            case 'deny':
                $transaksi->status = 'deny';
                foreach ($transaksi->detailTransaksi as $detail) {
                    $buku = Buku::find($detail->buku_id);
                    if ($buku && $buku->stokHarga) {
                        $buku->stokHarga->stok += $detail->jumlah;
                        $buku->stokHarga->save();
                    }
                }
                break;

            case 'cancel':
            case 'expire':
                $transaksi->status = $transactionStatus;
                break;

            default:
                $transaksi->status = 'unknown';
                break;
        }

        $transaksi->save();
        return response()->json(['message' => 'Notifikasi diterima', 'status' => $transactionStatus]);
    }
}
