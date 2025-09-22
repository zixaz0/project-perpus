<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalBuku  = Buku::count();
        $totalKasir = User::where('role', 'kasir')->count();
        $buku       = Buku::all(); // buat modal

        return view('admin.dashboard', compact('totalBuku', 'totalKasir', 'buku'));
    }
}