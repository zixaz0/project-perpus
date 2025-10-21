<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TransaksiController extends Controller
{
    /**
     * Tampilkan halaman keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);

        // Update stok dari database untuk memastikan data terkini
        foreach ($cart as $buku_id => $item) {
            $stokHarga = \App\Models\StokHarga::where('buku_id', $buku_id)->first();
            if ($stokHarga) {
                $cart[$buku_id]['stok'] = $stokHarga->stok;

                // Kurangi qty jika melebihi stok
                if ($item['qty'] > $stokHarga->stok) {
                    $cart[$buku_id]['qty'] = $stokHarga->stok;
                }
            }
        }

        session()->put('cart', $cart);

        return view('kasir.transaksi.index', compact('cart'));
    }

    /**
     * Tambah buku ke keranjang (DENGAN AJAX SUPPORT)
     */
    public function addToCart(Request $request, Buku $buku)
    {
        $stokHarga = $buku->stokHarga;

        if (! $stokHarga || $stokHarga->stok <= 0 || $stokHarga->harga <= 0) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku ini tidak tersedia untuk dijual.',
                ], 400);
            }

            return back()->with('error', 'Buku ini tidak tersedia untuk dijual.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            // Cek stok sebelum nambah qty
            if ($cart[$buku->id]['qty'] + 1 > $stokHarga->stok) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Stok tidak mencukupi untuk buku ini.',
                    ], 400);
                }

                return back()->with('error', 'Stok tidak mencukupi untuk buku ini.');
            }
            $cart[$buku->id]['qty']++;
        } else {
            $cart[$buku->id] = [
                'judul_buku' => $buku->judul_buku,
                'harga' => $stokHarga->harga,
                'qty' => 1,
                'stok' => $stokHarga->stok,
                'cover_buku' => $buku->cover_buku ?? null,
            ];
        }

        session()->put('cart', $cart);

        // Return JSON jika AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan ke keranjang.',
                'cart' => $cart,
            ]);
        }

        return back()->with('success', 'Buku berhasil ditambahkan ke keranjang.');
    }

    /**
     * Update quantity (DENGAN AJAX SUPPORT)
     */
public function updateQty(Request $request, $buku)
{
    // ✅ Konversi $buku ke ID (bisa string atau object)
    $id = is_object($buku) ? $buku->id : $buku;
    
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $newQty = (int) $request->qty;

        // Validasi stok
        $bukuModel = Buku::findOrFail($id);
        $stokHarga = $bukuModel->stokHarga;

        if (!$stokHarga) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data stok tidak ditemukan.',
                ], 400);
            }

            return back()->with('error', 'Data stok tidak ditemukan.');
        }

        if ($newQty > $stokHarga->stok) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $stokHarga->stok,
                ], 400);
            }

            return back()->with('error', 'Stok tidak mencukupi.');
        }

        if ($newQty <= 0) {
            // Kalau qty < 1 hapus item
            unset($cart[$id]);
        } else {
            // Update qty normal
            $cart[$id]['qty'] = $newQty;
            $cart[$id]['stok'] = $stokHarga->stok;
            $cart[$id]['harga'] = $stokHarga->harga; // ✅ Pastikan harga terupdate
        }

        session()->put('cart', $cart);
    }

    if ($request->ajax() || $request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Keranjang diperbarui.',
            'cart' => $cart,
        ]);
    }

    return back();
}

    /**
     * Hapus item dari keranjang (DENGAN AJAX SUPPORT)
     */
    public function removeFromCart(Request $request, $id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->put('cart', $cart);
        }

        // Return JSON jika AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil dihapus dari keranjang.',
                'cart' => $cart,
            ]);
        }

        return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    /**
     * Clear cart (DENGAN AJAX SUPPORT)
     */
    public function clear(Request $request)
    {
        session()->forget('cart');

        // Return JSON jika AJAX request
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan!',
            ]);
        }

        return redirect()->back()->with('success', 'Keranjang berhasil dikosongkan!');
    }

    /**
     * Proses checkout â†’ simpan transaksi ke DB
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang masih kosong!',
                ], 400);
            }

            return back()->with('error', 'Keranjang masih kosong!');
        }

        // Validasi stok & harga sebelum transaksi dibuat
        foreach ($cart as $buku_id => $item) {
            $stokHarga = \App\Models\StokHarga::where('buku_id', $buku_id)->first();

            if (! $stokHarga) {
                $message = 'Data stok untuk buku tidak ditemukan.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }

                return back()->with('error', $message);
            }

            if ($stokHarga->stok < $item['qty']) {
                $message = 'Stok '.$stokHarga->buku->judul_buku.' tidak mencukupi.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }

                return back()->with('error', $message);
            }

            if ($stokHarga->harga <= 0) {
                $message = 'Buku '.$stokHarga->buku->judul_buku.' belum memiliki harga.';
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => $message], 400);
                }

                return back()->with('error', $message);
            }
        }

        // Hitung total setelah validasi
        $total = collect($cart)->sum(fn ($item) => $item['harga'] * $item['qty']);

        // Bersihkan input dari karakter non-digit
        $diskon = preg_replace('/[^0-9]/', '', $request->input('diskon', 0));
        $diskon = (float) $diskon;
        $dibayar = preg_replace('/[^0-9]/', '', $request->input('dibayar', 0));
        $dibayar = (float) $dibayar;

        $subtotal = $total - $diskon;
        $kembalian = $dibayar - $subtotal;

        // Validasi pembayaran untuk cash
        $metodeBayar = $request->input('metode_bayar', 'cash');
        if ($metodeBayar === 'cash' && $dibayar < $subtotal) {
            $message = 'Uang dibayar kurang dari subtotal.';
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 400);
            }

            return back()->with('error', $message);
        }

        // Mulai transaksi database
        DB::beginTransaction();
        try {
            // Simpan transaksi
            $transaksi = Transaksi::create([
                'kasir_id' => auth()->id(),
                'total_harga' => $total,
                'diskon' => $diskon,
                'subtotal' => $subtotal,
                'dibayar' => $dibayar,
                'kembalian' => $kembalian,
                'metode_bayar' => $metodeBayar,
            ]);

            // Simpan detail + kurangi stok
            foreach ($cart as $buku_id => $item) {
                $stokHarga = \App\Models\StokHarga::where('buku_id', $buku_id)->first();

                TransaksiItem::create([
                    'transaksi_id' => $transaksi->id,
                    'buku_id' => $buku_id,
                    'qty' => $item['qty'],
                    'harga_satuan' => $stokHarga->harga,
                    'subtotal' => $stokHarga->harga * $item['qty'],
                ]);

                // Kurangi stok
                $stokHarga->stok -= $item['qty'];
                $stokHarga->save();
            }

            DB::commit();

            // Kosongkan keranjang
            session()->forget('cart');

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'transaksi_id' => $transaksi->id,
                    'message' => 'Transaksi berhasil disimpan.',
                ]);
            }

            return redirect()->route('kasir.transaksi.struk', $transaksi->id)
                ->with('success', 'Transaksi berhasil disimpan.');

        } catch (\Exception $e) {
            DB::rollBack();

            $message = 'Gagal memproses transaksi: '.$e->getMessage();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $message,
                ], 500);
            }

            return back()->with('error', $message);
        }
    }

    /**
     * Cetak struk
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with('items.buku')->findOrFail($id);

        return view('kasir.transaksi.struk', compact('transaksi'));
    }

    /**
     * Riwayat transaksi kasir
     */
    public function riwayat()
    {
        $transaksi = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->latest()
            ->paginate(10);

        return view('kasir.riwayat_transaksi.index', compact('transaksi'));
    }

    /**
     * Riwayat transaksi admin
     */
    public function riwayatadmin()
    {
        $transaksi = Transaksi::with(['kasir', 'items.buku', 'refundBy'])
            ->latest()
            ->paginate(10);

        return view('admin.riwayat_transaksi.index', compact('transaksi'));
    }

    /**
     * âœ… REFUND dengan BATAS WAKTU 24 JAM - FIXED: Cek semua admin
     */
    public function refund(Request $request, $id)
    {
        // Validasi input
        $request->validate([
            'admin_password' => 'required|string',
        ]);

        // Cari transaksi
        $transaksi = Transaksi::with('items')->find($id);

        if (! $transaksi) {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi tidak ditemukan.',
            ], 404);
        }

        // âœ… CEK BATAS WAKTU 24 JAM
        $batasWaktu = Carbon::parse($transaksi->created_at)->addHours(24);
        $sekarang = Carbon::now();

        if ($sekarang->greaterThan($batasWaktu)) {
            $waktuLewat = $sekarang->diffForHumans($batasWaktu, true);

            return response()->json([
                'success' => false,
                'message' => 'Refund tidak dapat dilakukan. Batas waktu refund 24 jam telah terlewati ('.$waktuLewat.' yang lalu).',
            ], 400);
        }

        // Cek apakah transaksi sudah direfund
        if ($transaksi->status === 'refund') {
            return response()->json([
                'success' => false,
                'message' => 'Transaksi ini sudah di-refund sebelumnya.',
            ], 400);
        }

        // âœ… PERBAIKAN: Verifikasi password ke SEMUA admin
        $allAdmins = User::where('role', 'admin')->get();

        if ($allAdmins->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Admin tidak ditemukan di sistem.',
            ], 404);
        }

        // Cek password ke semua admin, cari yang cocok
        $authenticatedAdmin = null;
        foreach ($allAdmins as $admin) {
            if (Hash::check($request->admin_password, $admin->password)) {
                $authenticatedAdmin = $admin;
                break; // Password cocok, keluar dari loop
            }
        }

        // Jika tidak ada yang cocok
        if (! $authenticatedAdmin) {
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

            // Update status transaksi - gunakan admin yang berhasil login
            $transaksi->update([
                'status' => 'refund',
                'refund_at' => now(),
                'refund_by' => $authenticatedAdmin->id, // âœ… ID admin yang benar
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil di-refund oleh '.$authenticatedAdmin->name.'. Stok telah dikembalikan.',
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal melakukan refund: '.$e->getMessage(),
            ], 500);
        }
    }
}