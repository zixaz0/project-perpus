<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class KasirController extends Controller
{
    public function index()
    {
        // Tanggal hari ini
        $today = Carbon::today();
        
        // 1. Transaksi Hari Ini
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)->count();
        
        // 2. Pendapatan Hari Ini
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->sum('subtotal');
        
        // 3. Buku Terjual Hari Ini
        $bukuTerjual = TransaksiItem::whereHas('transaksi', function($query) use ($today) {
            $query->whereDate('created_at', $today);
        })->sum('qty');
        
        // 4. Total Buku (dari stok)
        $totalBuku = DB::table('stok_harga')->sum('stok');
        
        // 5. Stok Habis (stok = 0)
        $stokHabis = Buku::with(['kategori', 'stokHarga'])
            ->whereHas('stokHarga', function($query) {
                $query->where('stok', '=', 0);
            })
            ->get()
            ->map(function($buku) {
                return (object)[
                    'judul' => $buku->judul_buku,
                    'stok' => $buku->stokHarga->stok ?? 0,
                    'kategori' => $buku->kategori
                ];
            });
        
        // 6. Stok Hampir Habis (stok 1-10)
        $stokMenipis = Buku::with(['kategori', 'stokHarga'])
            ->whereHas('stokHarga', function($query) {
                $query->where('stok', '>', 0)
                      ->where('stok', '<=', 10);
            })
            ->get()
            ->map(function($buku) {
                return (object)[
                    'judul' => $buku->judul_buku,
                    'stok' => $buku->stokHarga->stok ?? 0,
                    'kategori' => $buku->kategori
                ];
            });
        
        // 7. Buku Terlaris Minggu Ini (DENGAN COVER BUKU)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $bukuTerlaris = TransaksiItem::select('buku_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
            })
            ->groupBy('buku_id')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->with('buku')
            ->get()
            ->map(function($item) {
                return (object)[
                    'judul' => $item->buku->judul_buku ?? 'Judul Buku',
                    'penulis' => $item->buku->pengarang ?? 'Penulis',
                    'cover_buku' => $item->buku->cover_buku ?? null, // TAMBAH COVER
                    'total_terjual' => $item->total_terjual
                ];
            });
        
        // 8. Transaksi Terbaru (5 transaksi terakhir hari ini)
        $transaksiTerbaru = Transaksi::whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function($transaksi) {
                return (object)[
                    'kode_transaksi' => 'TRX-' . str_pad($transaksi->id, 6, '0', STR_PAD_LEFT),
                    'created_at' => $transaksi->created_at,
                    'total' => $transaksi->subtotal
                ];
            });
        
        // 9. Data Grafik Penjualan Mingguan (7 hari terakhir)
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Transaksi::whereDate('created_at', $date)->count();
            $grafikData[] = $count;
        }
        
        return view('kasir.dashboard', compact(
            'transaksiHariIni',
            'pendapatanHariIni',
            'bukuTerjual',
            'totalBuku',
            'stokHabis',
            'stokMenipis',
            'bukuTerlaris',
            'transaksiTerbaru',
            'grafikData'
        ));
    }
}