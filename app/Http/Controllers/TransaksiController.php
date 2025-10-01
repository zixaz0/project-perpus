<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Buku;
use App\Models\Transaksi;
use App\Models\TransaksiItem;

class TransaksiController extends Controller
{
    /**
     * Tampilkan halaman keranjang
     */
    public function index()
    {
        $cart = session()->get('cart', []);
        return view('kasir.transaksi.index', compact('cart'));
    }

    /**
     * Tambah buku ke keranjang
     */
    public function addToCart(Buku $buku)
    {
        $stokHarga = $buku->stokHarga;

        if (!$stokHarga || $stokHarga->stok <= 0 || $stokHarga->harga <= 0) {
            return back()->with('error', 'Buku ini tidak tersedia untuk dijual.');
        }

        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            // Cek stok sebelum nambah qty
            if ($cart[$buku->id]['qty'] + 1 > $stokHarga->stok) {
                return back()->with('error', 'Stok tidak mencukupi untuk buku ini.');
            }
            $cart[$buku->id]['qty']++;
        } else {
            $cart[$buku->id] = [
                'judul_buku' => $buku->judul_buku,
                'harga'      => $stokHarga->harga,
                'qty'        => 1,
                'cover_buku' => $buku->cover_buku ?? null,
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success',);
    }

    /**
     * Hapus item dari keranjang
     */
    public function removeFromCart($buku_id)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$buku_id])) {
            unset($cart[$buku_id]);
            session()->put('cart', $cart);
        }

        return back()->with('success', 'Buku berhasil dihapus dari keranjang.');
    }

    /**
     * Proses checkout → simpan transaksi ke DB
     */
    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang masih kosong!');
        }

        // 🔎 Validasi stok & harga sebelum transaksi dibuat
        foreach ($cart as $buku_id => $item) {
            $stokHarga = \App\Models\StokHarga::where('buku_id', $buku_id)->first();

            if (!$stokHarga) {
                return back()->with('error', 'Data stok untuk buku tidak ditemukan.');
            }

            if ($stokHarga->stok < $item['qty']) {
                return back()->with('error', 'Stok ' . $stokHarga->buku->judul_buku . ' tidak mencukupi.');
            }

            if ($stokHarga->harga <= 0) {
                return back()->with('error', 'Buku ' . $stokHarga->buku->judul_buku . ' belum memiliki harga.');
            }
        }

        // ✅ Hitung total setelah validasi
        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        // Bersihkan input dari karakter non-digit
        $diskon = preg_replace('/[^0-9]/', '', $request->input('diskon', 0));
        $diskon = (float) $diskon;
        $dibayar = preg_replace('/[^0-9]/', '', $request->input('dibayar', 0));
        $dibayar = (float) $dibayar;
        $subtotal = $total - $diskon;
        $kembalian = $dibayar - $subtotal;
        $kembalian = $dibayar - $subtotal;

        if ($dibayar < $subtotal) {
            return back()->with('error', 'Uang dibayar kurang dari subtotal.');
        }

        // ✅ Simpan transaksi
        $transaksi = Transaksi::create([
            'kasir_id'    => auth()->id(),
            'total_harga' => $total,
            'diskon'      => $diskon,
            'subtotal'    => $subtotal,
            'dibayar'     => $dibayar,
            'kembalian'   => $kembalian,
            'metode_bayar' => $request->input('metode_bayar', 'cash'),
        ]);

        // ✅ Simpan detail + kurangi stok
        foreach ($cart as $buku_id => $item) {
            $stokHarga = \App\Models\StokHarga::where('buku_id', $buku_id)->first();

            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'buku_id'      => $buku_id,
                'qty'          => $item['qty'],
                'harga_satuan' => $stokHarga->harga, // ambil dari DB biar aman
                'subtotal'     => $stokHarga->harga * $item['qty'],
            ]);

            // Kurangi stok
            $stokHarga->stok -= $item['qty'];
            $stokHarga->save();
        }

        // ✅ Kosongkan keranjang
        session()->forget('cart');

        return redirect()->route('kasir.transaksi.struk', $transaksi->id)
            ->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Cetak struk
     */
    public function struk($id)
    {
        $transaksi = Transaksi::with('items.buku')->findOrFail($id);
        return view('kasir.transaksi.struk', compact('transaksi'));
    }
    public function updateQty(Request $request, Buku $buku)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            $newQty = (int) $request->qty;

            if ($newQty <= 0) {
                // Kalau qty < 1 hapus item
                unset($cart[$buku->id]);
            } else {
                // Update qty normal
                $cart[$buku->id]['qty'] = $newQty;
            }

            session()->put('cart', $cart);
        }

        return back();
    }
}
