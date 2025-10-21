<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class OwnerController extends Controller
{
    public function index()
    {
        // Statistik Utama
        $totalBuku = Buku::count();
        $totalPegawai = User::whereIn('role', ['kasir', 'admin'])->count();
        
        // Statistik Transaksi Bulan Ini (TIDAK TERMASUK REFUND)
        $bulanIni = Carbon::now()->startOfMonth();
        $transaksisBulanIni = Transaksi::where('created_at', '>=', $bulanIni)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
            ->get();
        
        $totalTransaksiBulanIni = $transaksisBulanIni->count();
        $totalPendapatanBulanIni = $transaksisBulanIni->sum('subtotal');
        
        // Transaksi Hari Ini (TIDAK TERMASUK REFUND)
        $hariIni = Carbon::today();
        $transaksiHariIni = Transaksi::whereDate('created_at', $hariIni)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
            ->get();
        $pendapatanHariIni = $transaksiHariIni->sum('subtotal');
        $transaksiCountHariIni = $transaksiHariIni->count();
        
        // Buku Terlaris Bulan Ini (Top 5) - TIDAK TERMASUK REFUND
        $bukuTerlaris = TransaksiItem::select('buku_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function($q) use ($bulanIni) {
                $q->where('created_at', '>=', $bulanIni)
                  ->where(function($query) {
                      $query->where('status', 'selesai')
                            ->orWhereNull('status');
                  });
            })
            ->groupBy('buku_id')
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->with('buku.stokHarga')
            ->get();
        
        // Stok Menipis (stok < 10 dan > 0)
        $stokMenipis = Buku::with('stokHarga', 'kategori')
            ->whereHas('stokHarga', function($q) {
                $q->where('stok', '>', 0)->where('stok', '<', 10);
            })
            ->limit(5)
            ->get();
        
        // Stok Habis (stok = 0)
        $stokHabis = Buku::with('stokHarga', 'kategori')
            ->whereHas('stokHarga', function($q) {
                $q->where('stok', '=', 0);
            })
            ->limit(5)
            ->get();
        
        // Grafik Pendapatan 7 Hari Terakhir (TIDAK TERMASUK REFUND)
        $grafikPendapatan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i);
            $pendapatan = Transaksi::whereDate('created_at', $tanggal->format('Y-m-d'))
                ->where(function($query) {
                    $query->where('status', 'selesai')
                          ->orWhereNull('status');
                })
                ->sum('subtotal');
            
            $grafikPendapatan[] = [
                'tanggal' => $tanggal->format('d M'),
                'pendapatan' => $pendapatan
            ];
        }
        
        // Transaksi Terbaru (TERMASUK SEMUA STATUS)
        $transaksiTerbaru = Transaksi::with('kasir', 'items.buku')
            ->orderBy('created_at', 'DESC')
            ->limit(5)
            ->get();
        
        // Activity Log Terbaru
        $activityLogs = ActivityLog::with('user')
            ->orderBy('created_at', 'DESC')
            ->limit(10)
            ->get();
        
        // Perbandingan Metode Pembayaran Bulan Ini (TIDAK TERMASUK REFUND)
        $metodeBayar = Transaksi::select('metode_bayar', DB::raw('COUNT(*) as jumlah'))
            ->where('created_at', '>=', $bulanIni)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
            ->groupBy('metode_bayar')
            ->get();
        
        // Total Buku Terjual Bulan Ini (TIDAK TERMASUK REFUND)
        $totalBukuTerjual = TransaksiItem::whereHas('transaksi', function($q) use ($bulanIni) {
            $q->where('created_at', '>=', $bulanIni)
              ->where(function($query) {
                  $query->where('status', 'selesai')
                        ->orWhereNull('status');
              });
        })->sum('qty');
        
        // Rata-rata Transaksi Per Hari
        $rataRataTransaksi = $totalTransaksiBulanIni > 0 
            ? $totalPendapatanBulanIni / $totalTransaksiBulanIni 
            : 0;

        return view('owner.dashboard', compact(
            'totalBuku',
            'totalPegawai',
            'totalTransaksiBulanIni',
            'totalPendapatanBulanIni',
            'pendapatanHariIni',
            'transaksiCountHariIni',
            'bukuTerlaris',
            'stokMenipis',
            'stokHabis',
            'grafikPendapatan',
            'transaksiTerbaru',
            'activityLogs',
            'metodeBayar',
            'totalBukuTerjual',
            'rataRataTransaksi'
        ));
    }
}