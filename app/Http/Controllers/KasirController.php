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
}