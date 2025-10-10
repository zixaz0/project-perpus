<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

class KasirController extends Controller
{
    public function __construct()
    {
        // Hanya role kasir yang bisa akses controller ini
        $this->middleware('role:kasir');
    }

    public function index()
    {
        return view('kasir.dashboard');
    }
    public function data_buku()
    {
        return view('kasir.data_buku');
    }
    public function transaksi()
    {
        return view('kasir.transaksi');
    }
    public function riwayat_transaksi()
    {
        return view('kasir.riwayat_transaksi');
    }
    public function dashboard()
    {
        return view('kasir.dashboard', [
            'transaksiHariIni' => 8,
            'pendapatanHariIni' => 250000,
            'bukuTerjual' => 14,
            'totalBuku' => 120,
            'grafikData' => [5, 7, 6, 9, 10, 4, 8],
            'transaksiTerbaru' => \App\Models\Transaksi::latest()->take(5)->get(),
        ]);
    }
}
