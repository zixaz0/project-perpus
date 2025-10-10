<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\Buku;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LaporanController extends Controller
{
    /**
     * Halaman Laporan Penjualan
     */
    public function index(Request $request)
    {
        $title = 'Laporan Penjualan';
        
        // Default periode: bulan ini
        $tanggal_awal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        
        // Query transaksi dengan filter tanggal
        $transaksis = Transaksi::with(['kasir', 'items.buku'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->latest()
            ->paginate(15);
        
        // Statistik
        $total_transaksi = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])->count();
        
        $total_pendapatan = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])->sum('subtotal');
        
        $total_diskon = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])->sum('diskon');
        
        $total_buku_terjual = TransaksiItem::whereHas('transaksi', function($query) use ($tanggal_awal, $tanggal_akhir) {
            $query->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ]);
        })->sum('qty');
        
        // Buku Terlaris
        $buku_terlaris = TransaksiItem::select('buku_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('created_at', [
                    Carbon::parse($tanggal_awal)->startOfDay(),
                    Carbon::parse($tanggal_akhir)->endOfDay()
                ]);
            })
            ->groupBy('buku_id')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->with('buku')
            ->get();
        
        return view('owner.laporan.index', compact(
            'title',
            'transaksis',
            'tanggal_awal',
            'tanggal_akhir',
            'total_transaksi',
            'total_pendapatan',
            'total_diskon',
            'total_buku_terjual',
            'buku_terlaris'
        ));
    }
    
    /**
     * Detail Transaksi
     */
    public function detail($id)
    {
        $transaksi = Transaksi::with(['kasir', 'items.buku'])->findOrFail($id);
        return view('owner.laporan.detail', compact('transaksi'));
    }
    
    /**
     * Export Laporan ke Print
     */
    public function print(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        
        $transaksis = Transaksi::with(['kasir', 'items.buku'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->latest()
            ->get();
        
        $total_pendapatan = $transaksis->sum('subtotal');
        $total_diskon = $transaksis->sum('diskon');
        $total_buku_terjual = $transaksis->sum(function($t) {
            return $t->items->sum('qty');
        });
        
        return view('owner.laporan.print', compact(
            'transaksis',
            'tanggal_awal',
            'tanggal_akhir',
            'total_pendapatan',
            'total_diskon',
            'total_buku_terjual'
        ));
    }
}