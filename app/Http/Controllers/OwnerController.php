<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

class OwnerController extends Controller
{
    public function __construct()
    {
        // Hanya role owner yang bisa akses controller ini
        $this->middleware('role:owner');
    }

    public function index()
    {
        return view('owner.dashboard');
    }
    public function data_buku()
    {
        return view('owner.data_buku');
    }
    public function data_pegawai()
    {
        return view('owner.data_pegawai');
    }
    public function laporan_penjualan()
    {
        return view('owner.laporan_penjualan');
    }
}