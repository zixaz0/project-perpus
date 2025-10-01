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
        $cart = session()->get('cart', []);

        if (isset($cart[$buku->id])) {
            $cart[$buku->id]['qty']++;
        } else {
            $cart[$buku->id] = [
                'judul_buku' => $buku->judul_buku,
                'harga'      => $buku->stokHarga->harga ?? 0,
                'qty'        => 1,
                'cover_buku' => $buku->cover_buku ?? null, // ✅ konsisten sesuai tabel
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

        $total = collect($cart)->sum(fn($item) => $item['harga'] * $item['qty']);
        $diskon = $request->input('diskon', 0);
        $subtotal = $total - $diskon;
        $dibayar = $request->input('dibayar');
        $kembalian = $dibayar - $subtotal;

        if ($dibayar < $subtotal) {
            return back()->with('error', 'Uang dibayar kurang dari subtotal.');
        }

        // Simpan transaksi
        $transaksi = Transaksi::create([
            'kasir_id'    => auth()->id(),
            'total_harga' => $total,
            'diskon'      => $diskon,
            'subtotal'    => $subtotal,
            'dibayar'     => $dibayar,
            'kembalian'   => $kembalian,
            'metode_bayar' => $request->input('metode_bayar', 'cash'),
        ]);

        // Simpan detail item
        foreach ($cart as $buku_id => $item) {
            TransaksiItem::create([
                'transaksi_id' => $transaksi->id,
                'buku_id'      => $buku_id,
                'qty'          => $item['qty'],
                'harga_satuan' => $item['harga'],
                'subtotal'     => $item['harga'] * $item['qty'],
            ]);
        }

        // Kosongkan keranjang
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
