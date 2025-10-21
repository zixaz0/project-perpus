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
        
        // 1. Transaksi Hari Ini (TIDAK TERMASUK REFUND)
        $transaksiHariIni = Transaksi::whereDate('created_at', $today)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
            ->count();
        
        // 2. Pendapatan Hari Ini (TIDAK TERMASUK REFUND)
        $pendapatanHariIni = Transaksi::whereDate('created_at', $today)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
            ->sum('subtotal');
        
        // 3. Buku Terjual Hari Ini (TIDAK TERMASUK REFUND)
        $bukuTerjual = TransaksiItem::whereHas('transaksi', function($query) use ($today) {
            $query->whereDate('created_at', $today)
                  ->where(function($q) {
                      $q->where('status', 'selesai')
                        ->orWhereNull('status');
                  });
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
        
        // 7. Buku Terlaris Minggu Ini (TIDAK TERMASUK REFUND)
        $startOfWeek = Carbon::now()->startOfWeek();
        $endOfWeek = Carbon::now()->endOfWeek();
        
        $bukuTerlaris = TransaksiItem::select('buku_id', DB::raw('SUM(qty) as total_terjual'))
            ->whereHas('transaksi', function($query) use ($startOfWeek, $endOfWeek) {
                $query->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                      ->where(function($q) {
                          $q->where('status', 'selesai')
                            ->orWhereNull('status');
                      });
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
        
        // 8. Transaksi Terbaru (5 transaksi terakhir hari ini) - TIDAK TERMASUK REFUND
        $transaksiTerbaru = Transaksi::whereDate('created_at', $today)
            ->where(function($query) {
                $query->where('status', 'selesai')
                      ->orWhereNull('status');
            })
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
        
        // 9. Data Grafik Penjualan Mingguan (7 hari terakhir) - TIDAK TERMASUK REFUND
        $grafikData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = Transaksi::whereDate('created_at', $date)
                ->where(function($query) {
                    $query->where('status', 'selesai')
                          ->orWhereNull('status');
                })
                ->count();
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
    public function refund(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'admin_password' => 'required|string',
        ]);

        // Cari transaksi
        $transaksi = Transaksi::with('items')->find($id);
        
        if (!$transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        // ✅ CEK BATAS WAKTU 24 JAM
        $batasWaktu = Carbon::parse($transaksi->created_at)->addHours(24);
        $sekarang = Carbon::now();

        if ($sekarang->greaterThan($batasWaktu)) {
            $waktuLewat = $sekarang->diffForHumans($batasWaktu, true);
            return response()->json([
                'success' => false,
                'message' => 'Refund tidak dapat dilakukan. Batas waktu refund 24 jam telah terlewati (' . $waktuLewat . ' yang lalu).',
            ], 400);
        }

        // Cek apakah transaksi sudah direfund
        if ($transaksi->status === 'refund') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini sudah di-refund sebelumnya.',
            ], 400);
        }

        // Verifikasi password admin - cari admin pertama yang ditemukan
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan di sistem.',
            ], 404);
        }

        if (!Hash::check($request->admin_password, $admin->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password admin salah!',
            ], 401);
        }

        // Proses refund
        DB::beginTransaction();
        try {
            // Kembalikan stok semua item
            foreach ($transaksi->items as $item) {
                $stokHarga = \App\Models\StokHarga::where('buku_id', $item->buku_id)->first();
                if ($stokHarga) {
                    $stokHarga->stok += $item->qty;
                    $stokHarga->save();
                }
            }

            // Update status transaksi
            $transaksi->update([
                'status' => 'refund',
                'refund_at' => now(),
                'refund_by' => $admin->id,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil di-refund. Stok telah dikembalikan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan refund: ' . $e->getMessage(),
            ], 500);
        }
    }
}