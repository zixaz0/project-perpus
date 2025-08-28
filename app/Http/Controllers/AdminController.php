<?php

namespace App\Http\Controllers;

use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        // Hanya role admin yang bisa akses controller ini
        $this->middleware('role:admin');
    }

    public function index()
    {
        return view('admin.dashboard', [
            'title' => 'Dashboard' 
        ]);
    }
    public function management_buku()
    {
        return view('admin.management_buku');
    }
    public function management_kasir()
    {
        return view('admin.management_kasir');
    }
    public function riwayat_transaksi()
    {
        return view('admin.riwayat_transaksi');
    }
}