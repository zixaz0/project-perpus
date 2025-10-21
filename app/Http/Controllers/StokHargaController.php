<?php

namespace App\Http\Controllers;

use App\Models\StokHarga;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StokHargaController extends Controller
{
    /**
     * Tampilkan semua data stok & harga
     */
public function index(Request $request)
    {
        $query = StokHarga::with('buku');

        // Fitur Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            
            $query->whereHas('buku', function($q) use ($search) {
                $q->where('judul_buku', 'like', "%{$search}%")
                  ->orWhere('kode_buku', 'like', "%{$search}%");
            });
        }

        // Urutkan berdasarkan terbaru dan paginate
        $stokHarga = $query->latest()->paginate(10);

        // Append query string untuk pagination agar search tetap aktif
        $stokHarga->appends($request->all());

        return view('admin.stok_harga.index', compact('stokHarga'));
    }

    /**
     * Form tambah stok & harga
     */
    public function create()
    {
        // ambil semua id buku yang sudah ada di stok_harga
        $bukuSudahAda = StokHarga::pluck('buku_id')->toArray();
    
        // ambil buku yang belum ada stok & harga
        $buku = Buku::whereNotIn('id', $bukuSudahAda)->get();
    
        return view('admin.stok_harga.create', compact('buku'));
    }    

    /**
     * Simpan stok & harga baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'buku_id' => 'required|exists:buku,id|unique:stok_harga,buku_id',
            'stok'    => 'required|integer|min:0',
            'harga'   => 'required|numeric|min:0',
        ],[
            'buku_id.required' => 'Buku harus dipilih.',
            'buku_id.exists'   => 'Buku tidak ditemukan.',
            'buku_id.unique'   => 'Buku ini sudah memiliki data stok & harga.',
            'stok.required'    => 'Stok harus diisi.',
            'stok.integer'     => 'Stok harus berupa angka.',
            'stok.min'         => 'Stok minimal 0.',
            'harga.required'   => 'Harga harus diisi.',
            'harga.numeric'    => 'Harga harus berupa angka.',
            'harga.min'        => 'Harga minimal 0.',
        ]);        

        StokHarga::create($request->only(['buku_id', 'stok', 'harga']));

        return redirect()->route('admin.stok_harga.index')
            ->with('success', 'Data stok & harga berhasil ditambahkan.');
    }

    /**
     * Form edit harga (tidak bisa edit stok)
     */
    public function edit(StokHarga $stok_harga)
    {
        $buku = Buku::all();
        return view('admin.stok_harga.edit', compact('stok_harga', 'buku'));
    }

    /**
     * Update harga saja (stok tidak diubah)
     */
    public function update(Request $request, StokHarga $stok_harga)
    {
        $request->validate([
            'harga' => 'required|numeric|min:0',
        ], [
            'harga.required' => 'Harga harus diisi.',
            'harga.numeric'  => 'Harga harus berupa angka.',
            'harga.min'      => 'Harga minimal 0.',
        ]);
    
        $stok_harga->update(['harga' => $request->harga]);
    
        return redirect()->route('admin.stok_harga.index')
            ->with('success', 'Harga berhasil diperbarui.');
    }

    /**
     * Form tambah stok
     */
    public function tambahStokForm(StokHarga $stok_harga)
    {
        return view('admin.stok_harga.tambah_stok', compact('stok_harga'));
    }

    /**
     * Proses tambah stok (menambahkan ke stok yang sudah ada)
     */
    public function tambahStok(Request $request, StokHarga $stok_harga)
    {
        $request->validate([
            'jumlah_tambah' => 'required|integer|min:1',
        ], [
            'jumlah_tambah.required' => 'Jumlah stok yang ditambahkan harus diisi.',
            'jumlah_tambah.integer'  => 'Jumlah stok harus berupa angka.',
            'jumlah_tambah.min'      => 'Jumlah stok minimal 1.',
        ]);

        // Tambahkan stok baru ke stok yang sudah ada
        $stok_harga->stok += $request->jumlah_tambah;
        $stok_harga->save();

        return redirect()->route('admin.stok_harga.index')
            ->with('success', "Berhasil menambahkan {$request->jumlah_tambah} stok. Total stok sekarang: {$stok_harga->stok}");
    }

    /**
     * Hapus stok & harga
     */
    public function destroy(StokHarga $stok_harga)
    {
        $stok_harga->delete();

        return redirect()->route('admin.stok_harga.index')
            ->with('success', 'Data stok & harga berhasil dihapus.');
    }
}