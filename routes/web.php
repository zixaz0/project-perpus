<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\KasirController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OwnerPegawaiController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



// Hanya admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/management_buku', [BukuController::class, 'index'])->name('admin.management_buku');
    Route::get('/admin/management_kasir', [UserController::class, 'KasirIndex'])->name('admin.management_kasir');
    Route::get('/admin/riwayat_transaksi', [AdminController::class, 'riwayat_transaksi'])->name('admin.riwayat_transaksi');
    // Buku
    Route::resource('buku', BukuController::class)->names('admin.buku');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    // Kasir Management (khusus admin)
    Route::prefix('admin')->group(function () {
        Route::get('/kasir', [UserController::class, 'kasirIndex'])->name('kasir.index');
        Route::get('/kasir/create', [UserController::class, 'kasirCreate'])->name('kasir.create');
        Route::post('/kasir', [UserController::class, 'kasirStore'])->name('kasir.store');
        Route::delete('/kasir/{id}', [UserController::class, 'kasirDestroy'])->name('kasir.destroy');
        Route::get('/kasir/{id}/edit', [UserController::class, 'kasirEdit'])->name('kasir.edit');
        Route::put('/kasir/{id}', [UserController::class, 'kasirUpdate'])->name('kasir.update');
    });
});

// Hanya owner
Route::middleware(['auth', RoleMiddleware::class . ':owner'])->group(function () {
    Route::get('/owner', [OwnerController::class, 'index'])->name('owner.dashboard');
    Route::get('/owner/data_buku', [BukuController::class, 'indexowner'])->name('owner.data_buku');
    Route::get('/owner/data_pegawai', [OwnerPegawaiController::class, 'index'])->name('owner.data_pegawai');
    Route::get('/owner/laporan_penjualan', [OwnerController::class, 'laporan_penjualan'])->name('owner.laporan_penjualan');
    Route::get('/owner/data_buku', [BukuController::class, 'indexowner'])->name('owner.data_buku');
    Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
        Route::resource('pegawai', OwnerPegawaiController::class);
    });
});

// Hanya kasir
Route::middleware(['auth', RoleMiddleware::class . ':kasir'])->group(function () {
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.dashboard');
    Route::get('/kasir/data_buku', [KasirController::class, 'data_buku'])->name('kasir.data_buku');
    Route::get('/kasir/transaksi', [KasirController::class, 'transaksi'])->name('kasir.transaksi');
    Route::get('/kasir/riwayat_transaksi', [KasirController::class, 'riwayat_transaksi'])->name('kasir.riwayat_transaksi');
});
