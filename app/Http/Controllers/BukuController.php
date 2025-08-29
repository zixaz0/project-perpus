<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Models\Kategori;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Management Buku';
        $query = Buku::query();
        $kategori = Kategori::all();

        // kalau ada pencarian
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where('judul_buku', 'like', "%$q%")
                ->orWhere('kode_buku', 'like', "%$q%")
                ->orWhere('penerbit', 'like', "%$q%")
                ->orWhere('pengarang', 'like', "%$q%");
        }

        // kalau datanya banyak, lebih enak pakai paginate
        $buku = $query->latest()->paginate(10);

        return view('admin.management_buku', compact('buku', 'title', 'kategori'));
    }

    public function indexowner(Request $request)
    {
        $title = 'Data Buku';
        $query = Buku::query();
        $kategori = Kategori::all();

        // kalau ada pencarian
        if ($request->filled('qu')) {
            $qu = $request->qu;
            $query->where('judul_buku', 'like', "%$qu%")
                ->orWhere('kode_buku', 'like', "%$qu%")
                ->orWhere('penerbit', 'like', "%$qu%")
                ->orWhere('pengarang', 'like', "%$qu%");
        }

        // kalau datanya banyak, lebih enak pakai paginate
        $buku = $query->latest()->paginate(10);

        return view('owner.data_buku', compact('buku', 'title', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.tambah_buku', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_buku'   => 'required|unique:buku',
            'judul_buku'  => 'required',
            'penerbit'    => 'required',
            'pengarang'   => 'required',
            'tahun_terbit' => 'required|date',
            'kategori_id' => 'required|exists:kategori,id',
            'cover_buku'  => 'image|mimes:jpg,png,jpeg|max:2048'
        ],
        [
            'kode_buku.required' => 'Kode buku harus diisi.',
            'kode_buku.unique'     => 'Kode buku sudah ada, silakan gunakan kode lain.',
            'judul_buku.required' => 'Judul buku harus diisi.',
            'penerbit.required' => 'Penerbit harus diisi.',
            'pengarang.required' => 'Pengarang harus diisi.',
            'tahun_terbit.required' => 'Tahun terbit harus diisi.',
            'kategori_id.required' => 'Kategori harus diisi.',
        ]
    );

        $data = $request->all();

        if ($request->hasFile('cover_buku')) {
            $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }

        Buku::create($data);

        return redirect()->route('admin.management_buku')->with('success', 'Buku berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $buku = Buku::findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.edit_buku', compact('buku', 'kategori'));
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_buku'   => 'required|string|max:50|unique:buku,kode_buku,' . $id,
            'judul_buku'  => 'required|string|max:255',
            'penerbit'    => 'required|string|max:255',
            'pengarang'   => 'required|string|max:255',
            'kategori_id' => 'required|exists:kategori,id',
            'tahun_terbit'=> 'required|date',
            'cover_buku'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
    
        $buku = Buku::findOrFail($id);
    
        $data = $request->only(['kode_buku','judul_buku','penerbit','pengarang','kategori_id','tahun_terbit']);
    
        if ($request->hasFile('cover_buku')) {
            $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }
    
        $buku->update($data);
    
        return redirect()->route('admin.management_buku')
            ->with('success', 'Buku berhasil diperbarui!');
    }    
    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();
            return redirect()->route('admin.management_buku')
                ->with('success', 'Buku berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.management_buku')
                ->with('error', 'Gagal menghapus buku!');
        }
    }
}
