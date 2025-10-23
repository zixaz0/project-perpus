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
        
        // Ambil parameter filter status
        $status_filter = $request->input('status'); // null, 'selesai', atau 'refund'
        
        // Query transaksi dengan filter tanggal DAN status
        $query = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ]);
        
        // Filter berdasarkan status jika ada
        if ($status_filter === 'selesai') {
            // Hanya transaksi yang BUKAN refund
            $query->where(function($q) {
                $q->where('status', '!=', 'refund')
                  ->orWhereNull('status');
            });
        } elseif ($status_filter === 'refund') {
            // Hanya transaksi refund
            $query->where('status', 'refund');
        }
        // Jika $status_filter === null, tampilkan semua (tidak ada filter tambahan)
        
        $transaksis = $query->latest()->paginate(15);
        
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
        $status_filter = $request->input('status'); // Ambil filter status untuk print juga
        
        // Query dengan filter status
        $query = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->whereBetween('created_at', [
                Carbon::parse($tanggal_awal)->startOfDay(),
                Carbon::parse($tanggal_akhir)->endOfDay()
            ]);
        
        // Filter berdasarkan status jika ada
        if ($status_filter === 'selesai') {
            $query->where(function($q) {
                $q->where('status', '!=', 'refund')
                  ->orWhereNull('status');
            });
        } elseif ($status_filter === 'refund') {
            $query->where('status', 'refund');
        }
        
        $transaksis = $query->latest()->get();
        
        // Hitung total berdasarkan transaksi yang sudah difilter
        $total_pendapatan = $transaksis->sum('subtotal');
        $total_diskon = $transaksis->sum('diskon');
        $total_buku_terjual = $transaksis->sum(function($t) {
            return $t->items->sum('qty');
        });
        
        // Statistik refund (untuk semua transaksi, bukan yang difilter)
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
        
        return view('owner.laporan.print', compact(
            'transaksis',
            'tanggal_awal',
            'tanggal_akhir',
            'total_pendapatan',
            'total_diskon',
            'total_buku_terjual',
            'total_refund',
            'total_nilai_refund',
            'status_filter'
        ));
    }
}