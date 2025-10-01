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
    public function index()
    {
        $stokHarga = StokHarga::with('buku')->paginate(10);
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
     * Form edit stok & harga
     */
    public function edit(StokHarga $stok_harga)
    {
        $buku = Buku::all();
        return view('admin.stok_harga.edit', compact('stok_harga', 'buku'));
    }

    /**
     * Update stok & harga
     */
    public function update(Request $request, StokHarga $stok_harga)
    {
        $request->validate([
            'buku_id' => [
                'required',
                'exists:buku,id',
                Rule::unique('stok_harga','buku_id')->ignore($stok_harga->id),
            ],
            'stok'    => 'required|integer|min:0',
            'harga'   => 'required|numeric|min:0',
        ]);
    
        $stok_harga->update($request->only(['buku_id', 'stok', 'harga']));
    
        return redirect()->route('admin.stok_harga.index')
            ->with('success', 'Data stok & harga berhasil diperbarui.');
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