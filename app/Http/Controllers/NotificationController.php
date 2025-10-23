<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\User;

class NotificationController extends Controller
{
    public function getNotifications()
    {
        try {
            $user = Auth::user();
            $notifications = [];

            if ($user->role === 'admin') {
                // Stok menipis (< 10)
                $lowStock = DB::table('stok_harga')
                    ->where('stok', '<', 10)
                    ->where('stok', '>', 0)
                    ->count();
                
                if ($lowStock > 0) {
                    $notifications[] = [
                        'type' => 'warning',
                        'icon' => 'fa-box',
                        'title' => 'Stok Menipis',
                        'message' => "$lowStock buku memiliki stok kurang dari 10",
                        'time' => 'Sekarang',
                        'link' => route('admin.stok_harga.index')
                    ];
                }

                // Stok habis
                $outOfStock = DB::table('stok_harga')
                    ->where('stok', '=', 0)
                    ->count();
                
                if ($outOfStock > 0) {
                    $notifications[] = [
                        'type' => 'danger',
                        'icon' => 'fa-exclamation-triangle',
                        'title' => 'Stok Habis',
                        'message' => "$outOfStock buku habis stoknya",
                        'time' => 'Sekarang',
                        'link' => route('admin.stok_harga.index')
                    ];
                }

                // Transaksi hari ini
                $todayTransactions = Transaksi::whereDate('created_at', today())->count();
                if ($todayTransactions > 0) {
                    $notifications[] = [
                        'type' => 'success',
                        'icon' => 'fa-shopping-cart',
                        'title' => 'Transaksi Hari Ini',
                        'message' => "$todayTransactions transaksi telah dilakukan",
                        'time' => 'Hari ini',
                        'link' => route('admin.riwayat_transaksi.index')
                    ];
                }

                // Total pendapatan hari ini
                $todayRevenue = Transaksi::whereDate('created_at', today())->sum('total_harga');
                if ($todayRevenue > 0) {
                    $notifications[] = [
                        'type' => 'success',
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'Pendapatan Hari Ini',
                        'message' => 'Rp ' . number_format($todayRevenue, 0, ',', '.'),
                        'time' => 'Hari ini',
                        'link' => route('admin.riwayat_transaksi.index')
                    ];
                }

                // Total kasir
                $totalKasir = User::where('role', 'kasir')->count();
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-users',
                    'title' => 'Total Kasir',
                    'message' => "$totalKasir kasir terdaftar",
                    'time' => 'Sekarang',
                    'link' => route('admin.kasir.index')
                ];
            }

            if ($user->role === 'kasir') {
                // Total penjualan kasir hari ini
                $todaySales = Transaksi::whereDate('created_at', today())
                    ->where('kasir_id', $user->id)
                    ->sum('total_harga');
                
                if ($todaySales > 0) {
                    $notifications[] = [
                        'type' => 'success',
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'Penjualan Anda Hari Ini',
                        'message' => 'Rp ' . number_format($todaySales, 0, ',', '.'),
                        'time' => 'Hari ini',
                        'link' => route('kasir.riwayat_transaksi.index')
                    ];
                }

                // Transaksi kasir hari ini
                $todayTransactions = Transaksi::whereDate('created_at', today())
                    ->where('kasir_id', $user->id)
                    ->count();
                
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-receipt',
                    'title' => 'Transaksi Anda Hari Ini',
                    'message' => "$todayTransactions transaksi berhasil",
                    'time' => 'Hari ini',
                    'link' => route('kasir.riwayat_transaksi.index')
                ];

                // Stok menipis (< 5)
                $lowStock = DB::table('stok_harga')
                    ->where('stok', '<', 5)
                    ->where('stok', '>', 0)
                    ->count();
                
                if ($lowStock > 0) {
                    $notifications[] = [
                        'type' => 'warning',
                        'icon' => 'fa-box',
                        'title' => 'Perhatian Stok',
                        'message' => "$lowStock buku stoknya hampir habis",
                        'time' => 'Sekarang',
                        'link' => route('kasir.buku.index')
                    ];
                }

                // Total buku tersedia
                $totalBuku = Buku::count();
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-book',
                    'title' => 'Buku Tersedia',
                    'message' => "$totalBuku buku dapat dijual",
                    'time' => 'Sekarang',
                    'link' => route('kasir.buku.index')
                ];
            }

            if ($user->role === 'owner') {
                // Pendapatan bulan ini
                $monthlyRevenue = Transaksi::whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year)
                    ->sum('total_harga');
                
                $notifications[] = [
                    'type' => 'success',
                    'icon' => 'fa-chart-line',
                    'title' => 'Pendapatan Bulan Ini',
                    'message' => 'Rp ' . number_format($monthlyRevenue, 0, ',', '.'),
                    'time' => 'Bulan ini',
                    'link' => route('owner.laporan.index')
                ];

                // Pendapatan hari ini
                $todayRevenue = Transaksi::whereDate('created_at', today())->sum('total_harga');
                if ($todayRevenue > 0) {
                    $notifications[] = [
                        'type' => 'success',
                        'icon' => 'fa-money-bill-wave',
                        'title' => 'Pendapatan Hari Ini',
                        'message' => 'Rp ' . number_format($todayRevenue, 0, ',', '.'),
                        'time' => 'Hari ini',
                        'link' => route('owner.laporan.index')
                    ];
                }

                // Stok kritis (< 5)
                $criticalStock = DB::table('stok_harga')
                    ->where('stok', '<', 5)
                    ->count();
                
                if ($criticalStock > 0) {
                    $notifications[] = [
                        'type' => 'danger',
                        'icon' => 'fa-exclamation-circle',
                        'title' => 'Stok Kritis',
                        'message' => "$criticalStock buku perlu restok segera",
                        'time' => 'Sekarang',
                        'link' => route('owner.buku.index')
                    ];
                }

                // Transaksi hari ini
                $todayTransactions = Transaksi::whereDate('created_at', today())->count();
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-shopping-cart',
                    'title' => 'Transaksi Hari Ini',
                    'message' => "$todayTransactions transaksi berhasil",
                    'time' => 'Hari ini',
                    'link' => route('owner.laporan.index')
                ];

                // Total pegawai
                $totalPegawai = User::whereIn('role', ['admin', 'kasir'])->count();
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-users',
                    'title' => 'Total Pegawai',
                    'message' => "$totalPegawai pegawai terdaftar",
                    'time' => 'Sekarang',
                    'link' => route('owner.pegawai.index')
                ];

                // Total buku
                $totalBuku = Buku::count();
                $notifications[] = [
                    'type' => 'info',
                    'icon' => 'fa-book',
                    'title' => 'Total Buku',
                    'message' => "$totalBuku buku terdaftar",
                    'time' => 'Sekarang',
                    'link' => route('owner.buku.index')
                ];
            }

            return response()->json([
                'count' => count($notifications),
                'notifications' => $notifications
            ]);

        } catch (\Exception $e) {
            Log::error('Notification error: ' . $e->getMessage());
            return response()->json([
                'count' => 0,
                'notifications' => [],
                'error' => $e->getMessage()
            ], 200);
        }
    }
}