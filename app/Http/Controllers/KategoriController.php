<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KategoriController extends Controller
{
    public function index()
    {
        // Ambil semua kategori, lalu group by kategori
        $kategori = Kategori::orderBy('kategori')
            ->get()
            ->groupBy('kategori');
    
        // Hitung jumlah kategori unik
        $totalKategori = \App\Models\Kategori::distinct('kategori')->count('kategori');
    
        // Kirim dua variabel ke view
        return view('admin.kategori.index', compact('kategori', 'totalKategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => ['required', 'string', 'max:255'],
            'jenis'    => ['required', 'string', 'max:255', Rule::unique('kategori', 'jenis')],
        ], [
            'kategori.required' => 'Kategori harus diisi.',
            'jenis.required'    => 'Jenis harus diisi.',
            'jenis.unique'      => 'Jenis sudah ada.',
        ]);

        Kategori::create($request->only(['kategori', 'jenis']));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        return view('admin.kategori.edit', compact('kategori'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        $request->validate([
            'kategori' => ['required', 'string', 'max:255'],
            'jenis'    => [
                'required',
                'string',
                'max:255',
                Rule::unique('kategori', 'jenis')->ignore($kategori->id),
            ],
        ], [
            'kategori.required' => 'Kategori harus diisi.',
            'jenis.required'    => 'Jenis harus diisi.',
            'jenis.unique'      => 'Jenis sudah ada.',
        ]);

        $kategori->update($request->only(['kategori', 'jenis']));

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')
            ->with('success', 'Kategori berhasil dihapus!');
    }
}
