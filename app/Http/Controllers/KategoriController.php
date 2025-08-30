<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::latest()->paginate(10);
        return view('admin.kategori.index', compact('kategori'));
    }

    public function create()
    {
        return view('admin.kategori.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kategori' => 'required|string|max:255|unique:kategori,kategori',
            'jenis' => 'required|string|max:255',
        ],
        [
            'kategori.required' => 'Kategori harus diisi.',
            'kategori.unique' => 'Kategori sudah ada, silakan gunakan kategori lain.',
            'jenis.required' => 'Jenis harus diisi.',
        ]
        );

        Kategori::create($request->only(['kategori', 'jenis']));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan!');
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
            'kategori' => 'required|string|max:255|unique:kategori,kategori,' . $kategori->id,
            'jenis' => 'required|string|max:255',
        ],[
            'kategori.required' => 'Kategori harus diisi.',
            'kategori.unique' => 'Kategori sudah ada, silakan gunakan kategori lain.',
            'jenis.required' => 'Jenis harus diisi.',
        ]
        );

        $kategori->update($request->only(['kategori', 'jenis']));

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
