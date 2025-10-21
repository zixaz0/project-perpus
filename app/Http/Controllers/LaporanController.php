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
        
        // Query transaksi dengan filter tanggal (semua transaksi termasuk refund untuk ditampilkan)
        $transaksis = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->latest()
            ->paginate(15);
        
        // Statistik - HANYA transaksi berhasil (tidak termasuk refund)
        $total_transaksi = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->where(function($query) {
            $query->where('status', '!=', 'refund')
                  ->orWhereNull('status');
        })
        ->count();
        
        $total_pendapatan = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->where(function($query) {
            $query->where('status', '!=', 'refund')
                  ->orWhereNull('status');
        })
        ->sum('subtotal');
        
        $total_diskon = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->where(function($query) {
            $query->where('status', '!=', 'refund')
                  ->orWhereNull('status');
        })
        ->sum('diskon');
        
        $total_buku_terjual = TransaksiItem::whereHas('transaksi', function($query) use ($tanggal_awal, $tanggal_akhir) {
            $query->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->where(function($q) {
                $q->where('status', '!=', 'refund')
                  ->orWhereNull('status');
            });
        })->sum('qty');
        
        // Statistik Refund
        $total_refund = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->where('status', 'refund')
        ->count();
        
        $total_nilai_refund = Transaksi::whereBetween('created_at', [
            Carbon::parse($tanggal_awal)->startOfDay(),
            Carbon::parse($tanggal_akhir)->endOfDay()
        ])
        ->where('status', 'refund')
        ->sum('subtotal');
        
        // Buku Terlaris (tidak termasuk transaksi yang di-refund)
        $buku_terlaris = TransaksiItem::select('buku_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function($query) use ($tanggal_awal, $tanggal_akhir) {
                $query->whereBetween('created_at', [
                    Carbon::parse($tanggal_awal)->startOfDay(),
                    Carbon::parse($tanggal_akhir)->endOfDay()
                ])
                ->where(function($q) {
                    $q->where('status', '!=', 'refund')
                      ->orWhereNull('status');
                });
            })
            ->groupBy('buku_id')
            ->orderBy('total_terjual', 'desc')
            ->limit(5)
            ->with('buku.stokHarga')
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
            'total_refund',
            'total_nilai_refund',
            'buku_terlaris'
        ));
    }
    
    /**
     * Detail Transaksi
     */
    public function detail($id)
    {
        $transaksi = Transaksi::with(['kasir', 'items.buku', 'refundBy'])->findOrFail($id);
        return view('owner.laporan.detail', compact('transaksi'));
    }
    
    /**
     * Export Laporan ke Print
     */
    public function print(Request $request)
    {
        $tanggal_awal = $request->input('tanggal_awal', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $tanggal_akhir = $request->input('tanggal_akhir', Carbon::now()->format('Y-m-d'));
        
        // Ambil semua transaksi termasuk yang di-refund untuk laporan lengkap
        $transaksis = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ])
            ->latest()
            ->get();
        
        // Hitung total hanya dari transaksi berhasil (tidak termasuk refund)
        $transaksi_berhasil = $transaksis->filter(function($t) {
            return $t->status !== 'refund';
        });
        
        $total_pendapatan = $transaksi_berhasil->sum('subtotal');
        $total_diskon = $transaksi_berhasil->sum('diskon');
        $total_buku_terjual = $transaksi_berhasil->sum(function($t) {
            return $t->items->sum('qty');
        });
        
        // Statistik refund
        $transaksi_refund = $transaksis->filter(function($t) {
            return $t->status === 'refund';
        });
        
        $total_refund = $transaksi_refund->count();
        $total_nilai_refund = $transaksi_refund->sum('subtotal');
        
        return view('owner.laporan.print', compact(
            'transaksis',
            'tanggal_awal',
            'tanggal_akhir',
            'total_pendapatan',
            'total_diskon',
            'total_buku_terjual',
            'total_refund',
            'total_nilai_refund'
        ));
    }
}