<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\StokHarga;
use Illuminate\Http\Request;

class BukuController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Management Buku';
        $query = Buku::with(['kategori', 'stokHarga']); // load stokHarga juga
        $kategori = Kategori::all()->groupBy('kategori');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul_buku', 'like', "%$q%")
                    ->orWhere('kode_buku', 'like', "%$q%")
                    ->orWhere('penerbit', 'like', "%$q%")
                    ->orWhere('pengarang', 'like', "%$q%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->latest()->paginate(10);

        return view('admin.buku.index', compact('buku', 'title', 'kategori'));
    }

    public function indexkasir(Request $request)
    {
        $title = 'Data Buku';
        $query = Buku::with(['kategori', 'stokHarga']);
        $kategori = Kategori::all()->groupBy('kategori');

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('judul_buku', 'like', "%$q%")
                    ->orWhere('kode_buku', 'like', "%$q%")
                    ->orWhere('penerbit', 'like', "%$q%")
                    ->orWhere('pengarang', 'like', "%$q%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->latest()->paginate(10);

        return view('kasir.buku.index', compact('buku', 'title', 'kategori'));
    }

    public function indexowner(Request $request)
    {
        $title = 'Data Buku';
        $query = Buku::with('stokHarga');
        $kategori = Kategori::all();

        if ($request->filled('qu')) {
            $qu = $request->qu;
            $query->where('judul_buku', 'like', "%$qu%")
                ->orWhere('kode_buku', 'like', "%$qu%")
                ->orWhere('penerbit', 'like', "%$qu%")
                ->orWhere('pengarang', 'like', "%$qu%");
        }

        $buku = $query->latest()->paginate(10);
        return view('owner.data_buku', compact('buku', 'title', 'kategori'));
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('admin.buku.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_buku'   => 'required',
            'penerbit'     => 'required',
            'pengarang'    => 'required',
            'tahun_terbit' => 'required|date',
            'kategori_id'  => 'required|exists:kategori,id',
            'cover_buku'   => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'stok'  => 'nullable|integer|min:0',
            'harga' => 'nullable|numeric|min:0',
        ]);

        $kategori = Kategori::findOrFail($request->kategori_id);

        // Prefix kategori & jenis
        $prefixKategori = strtoupper(substr($kategori->kategori, 0, 2));
        $prefixJenis    = strtoupper(substr($kategori->jenis, 0, 2));

        // Cari buku terakhir
        $lastBook = Buku::where('kategori_id', $kategori->id)->orderBy('id', 'desc')->first();
        $newNumber = $lastBook ? ((int) substr($lastBook->kode_buku, -3) + 1) : 1;

        $kode_buku = 'BK' . $prefixKategori . $prefixJenis . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        $data = $request->only(['judul_buku', 'penerbit', 'pengarang', 'kategori_id', 'tahun_terbit']);
        $data['kode_buku'] = $kode_buku;

        if ($request->hasFile('cover_buku')) {
            $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
        }

        $buku = Buku::create($data);

        // Simpan stok & harga
            StokHarga::create([
                'buku_id' => $buku->id,
                'stok'    => $request->stok ?? 0,
                'harga'   => $request->harga ?? 0,
        ]);

        return redirect()->route('admin.buku.index')->with('success', 'Buku berhasil ditambahkan dengan kode: ' . $kode_buku);
    }

    public function edit($id)
    {
        $buku = Buku::with('stokHarga')->findOrFail($id);
        $kategori = Kategori::all();
        return view('admin.buku.edit', compact('buku', 'kategori'));
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'judul_buku'   => 'required|string|max:255',
                'penerbit'     => 'required|string|max:255',
                'pengarang'    => 'required|string|max:255',
                'kategori_id'  => 'required|exists:kategori,id',
                'tahun_terbit' => 'required|date',
                'cover_buku'   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
                'stok'  => 'nullable|integer|min:0',
                'harga' => 'nullable|numeric|min:0',
            ]);

            $buku = Buku::findOrFail($id);

            $data = $request->only(['judul_buku', 'penerbit', 'pengarang', 'kategori_id', 'tahun_terbit']);

            // 📌 Jika kategori berubah → generate kode baru
            if ($buku->kategori_id != $request->kategori_id) {
                $kategori = Kategori::findOrFail($request->kategori_id);

                $prefixKategori = strtoupper(substr($kategori->kategori, 0, 2));
                $prefixJenis    = strtoupper(substr($kategori->jenis, 0, 2));

                $lastBook = Buku::where('kategori_id', $kategori->id)->orderBy('id', 'desc')->first();
                $newNumber = $lastBook ? ((int) substr($lastBook->kode_buku, -3) + 1) : 1;

                $data['kode_buku'] = 'BK' . $prefixKategori . $prefixJenis . str_pad($newNumber, 3, '0', STR_PAD_LEFT);
            }

            if ($request->hasFile('cover_buku')) {
                $data['cover_buku'] = $request->file('cover_buku')->store('covers', 'public');
            }

            $buku->update($data);

            // Update stok & harga
            if ($buku->stokHarga) {
                $buku->stokHarga->update([
                    'stok'  => $request->stok,
                    'harga' => $request->harga,
                ]);
            } else {
                StokHarga::create([
                    'buku_id' => $buku->id,
                    'stok'    => $request->stok,
                    'harga'   => $request->harga,
                ]);
            }

            return redirect()->route('admin.buku.index')
                ->with('success', 'Buku berhasil diperbarui!');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Gagal update: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        try {
            $buku = Buku::findOrFail($id);
            $buku->delete();
            return redirect()->route('admin.buku.index')
                ->with('success', 'Buku berhasil dihapus!');
        } catch (\Exception $e) {
            return redirect()->route('admin.buku.index')
                ->with('error', 'Gagal menghapus buku!');
        }
    }

    public function generateKode($kategori_id)
    {
        $kategori = Kategori::findOrFail($kategori_id);

        $prefixKategori = strtoupper(substr($kategori->kategori, 0, 2));
        $prefixJenis    = strtoupper(substr($kategori->jenis, 0, 2));

        $lastBook = Buku::where('kategori_id', $kategori_id)->orderBy('id', 'desc')->first();
        $newNumber = $lastBook ? ((int) substr($lastBook->kode_buku, -3) + 1) : 1;

        $kode_buku = 'BK' . $prefixKategori . $prefixJenis . str_pad($newNumber, 3, '0', STR_PAD_LEFT);

        return response()->json(['kode_buku' => $kode_buku]);
    }
}
