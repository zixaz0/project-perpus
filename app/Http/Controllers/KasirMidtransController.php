<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Facades\Http;

class KasirMidtransController extends Controller
{
    public function getToken(Request $request)
    {
        \Midtrans\Config::$serverKey = config('midtrans.server_key');
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $orderId = 'ORD-' . time();

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $request->total,
            ],
            'customer_details' => [
                'first_name' => 'Kasir',
                'email' => 'kasir@example.com',
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        return response()->json(['token' => $snapToken, 'order_id' => $orderId]);
    }

    public function cancelPayment(Request $request)
    {
        $orderId = $request->order_id;

        if (!$orderId) {
            return response()->json(['status' => 'error', 'message' => 'Order ID tidak ditemukan'], 400);
        }

        $serverKey = config('midtrans.server_key');

        // Cek status transaksi dulu
        $statusUrl = 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/status';

        try {
            $statusResponse = Http::withBasicAuth($serverKey, '')->get($statusUrl);

            if (!$statusResponse->successful()) {
                // Jika transaksi tidak ditemukan di Midtrans, anggap sudah dibatalkan
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi tidak ditemukan atau sudah dibatalkan',
                ]);
            }

            $statusData = $statusResponse->json();
            $transactionStatus = $statusData['transaction_status'] ?? '';

            // Jika sudah cancel/expire/deny, return success
            if (in_array($transactionStatus, ['cancel', 'expire', 'deny'])) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi sudah dibatalkan sebelumnya (status: ' . $transactionStatus . ')',
                ]);
            }

            // Jika settlement/capture, tidak bisa dibatalkan
            if (in_array($transactionStatus, ['settlement', 'capture'])) {
                return response()->json(
                    [
                        'status' => 'error',
                        'message' => 'Transaksi sudah berhasil, tidak dapat dibatalkan',
                    ],
                    400,
                );
            }

            // Coba cancel dulu
            $cancelUrl = 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/cancel';

            $cancelResponse = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($cancelUrl);

            if ($cancelResponse->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pembayaran berhasil dibatalkan di Midtrans',
                ]);
            }

            // Jika cancel gagal, coba expire sebagai alternatif
            $expireUrl = 'https://api.sandbox.midtrans.com/v2/' . $orderId . '/expire';

            $expireResponse = Http::withBasicAuth($serverKey, '')
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ])
                ->post($expireUrl);

            if ($expireResponse->successful()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Transaksi berhasil di-expire (dibatalkan)',
                ]);
            }

            // Jika semua gagal, return error
            $errorData = $cancelResponse->json();
            return response()->json(
                [
                    'status' => 'error',
                    'message' => $errorData['status_message'] ?? 'Gagal membatalkan pembayaran. Silakan coba lagi atau tunggu transaksi expired otomatis.',
                ],
                400,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }
}
