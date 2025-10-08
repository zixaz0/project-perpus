<?php

namespace App\Providers;


use Illuminate\Support\ServiceProvider;
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\StokHarga;
use App\Models\Transaksi;
use App\Models\TransaksiItem;
use App\Models\User;
use App\Observers\ModelActivityObserver;
use Database\Seeders\KategoriSeeder;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Buku::observe(ModelActivityObserver::class);
        Kategori::observe(ModelActivityObserver::class);
        StokHarga::observe(ModelActivityObserver::class);
        Transaksi::observe(ModelActivityObserver::class);
        TransaksiItem::observe(ModelActivityObserver::class);
        User::observe(ModelActivityObserver::class);
    }
}