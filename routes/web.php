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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\StokHargaController;
use App\Http\Controllers\TransaksiController;

Route::get('/', function () {
    return view('login');
});

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.post');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::post('/profile/update-photo', [ProfileController::class, 'updatePhoto'])
    ->name('profile.updatePhoto')
    ->middleware('auth');



// Hanya admin
Route::middleware(['auth', RoleMiddleware::class . ':admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/buku.index', [BukuController::class, 'index'])->name('admin.buku.index');
    Route::get('/admin/kasir.index', [UserController::class, 'KasirIndex'])->name('admin.kasir.index');
    Route::get('/admin/riwayat_transaksi.index', [AdminController::class, 'riwayat_transaksi'])->name('admin.riwayat_transaksi.index');
    // Buku
    Route::resource('buku', BukuController::class)->names('admin.buku');
    Route::get('/buku/{id}/edit', [BukuController::class, 'edit'])->name('buku.edit');
    Route::put('/buku/{id}', [BukuController::class, 'update'])->name('buku.update');
    Route::get('/admin/buku/generate-kode/{kategori_id}', [App\Http\Controllers\BukuController::class, 'generateKode'])->name('admin.buku.generateKode');
    
    // Stok & Harga (resource dengan prefix admin)
    Route::resource('stok_harga', StokHargaController::class)->names('admin.stok_harga');
    
    // Kasir Management (khusus admin)
    Route::prefix('admin')->group(function () {
        Route::get('/kasir', [UserController::class, 'kasirIndex'])->name('kasir.index');
        Route::get('/kasir/create', [UserController::class, 'kasirCreate'])->name('kasir.create');
        Route::post('/kasir', [UserController::class, 'kasirStore'])->name('kasir.store');
        Route::delete('/kasir/{id}', [UserController::class, 'kasirDestroy'])->name('kasir.destroy');
        Route::get('/kasir/{id}/edit', [UserController::class, 'kasirEdit'])->name('kasir.edit');
        Route::put('/kasir/{id}', [UserController::class, 'kasirUpdate'])->name('kasir.update');
        Route::get('/kasir/{id}', [UserController::class, 'show'])->name('kasir.show');
    // Kategori
    Route::resource('kategori', \App\Http\Controllers\KategoriController::class)
    ->names('admin.kategori')
    ->except(['show']);
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
Route::middleware(['auth', RoleMiddleware::class . ':kasir'])->prefix('kasir')->name('kasir.')->group(function () {
    
    // Dashboard Kasir
    Route::get('/', [KasirController::class, 'index'])->name('dashboard');

    // Data Buku
    Route::get('/buku', [BukuController::class, 'indexkasir'])->name('buku.index');

    // Riwayat Transaksi
// Riwayat Transaksi
Route::get('/riwayat-transaksi', [TransaksiController::class, 'riwayat'])
    ->name('riwayat_transaksi.index');


    // Transaksi / Keranjang
    Route::prefix('transaksi')->name('transaksi.')->group(function () {
        Route::get('/', [TransaksiController::class, 'index'])->name('index'); // lihat keranjang
        Route::post('add/{buku}', [TransaksiController::class, 'addToCart'])->name('add'); // tambah buku
        Route::delete('remove/{buku}', [TransaksiController::class, 'removeFromCart'])->name('remove'); // hapus buku
        Route::post('checkout', [TransaksiController::class, 'checkout'])->name('checkout'); // simpan transaksi
        Route::get('struk/{id}', [TransaksiController::class, 'struk'])->name('struk'); // cetak struk
        Route::patch('update/{buku}', [TransaksiController::class, 'updateQty'])->name('update');
        Route::get('/reset-cart', function () {
            session()->forget('cart');
            return 'Cart berhasil direset!';
        });   
    });
});
