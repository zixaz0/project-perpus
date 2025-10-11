<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;
use App\Models\Kategori;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $totalBuku     = Buku::count();
        $totalKasir    = User::where('role', 'kasir')->count();
        $totalKategori = Kategori::count();

        // Query buku + relasi kategori
        $query = Buku::with('kategori');

        // Filter pencarian (judul / kode)
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qBuilder) use ($q) {
                $qBuilder->where('judul_buku', 'like', "%{$q}%")
                    ->orWhere('kode_buku', 'like', "%{$q}%");
            });
        }

        // Filter kategori
        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        $buku = $query->latest()->get();

        // Ambil kategori untuk dropdown
        $kategori = Kategori::orderBy('kategori')->get()->groupBy('kategori');

        // Stok Habis (stok = 0)
        $stokHabis = Buku::with(['kategori', 'stokHarga'])
            ->whereHas('stokHarga', function ($query) {
                $query->where('stok', '=', 0);
            })
            ->orderBy('judul_buku')
            ->get();

        // Stok menipis (stok < 10)
        $stokMenipis = Buku::with(['kategori', 'stokHarga'])
            ->whereHas('stokHarga', function ($query) {
                $query->where('stok', '>', 0)
                      ->where('stok', '<', 10);
            })
            ->orderBy('judul_buku')
            ->get();

        return view('admin.dashboard', compact(
            'totalBuku',
            'totalKasir',
            'totalKategori',
            'buku',
            'kategori',
            'stokHabis',
            'stokMenipis'
        ));
    }
}
