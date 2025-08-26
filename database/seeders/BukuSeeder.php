<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Buku;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Buku::create([
            'kode_buku'   => 'BK001',
            'judul_buku'  => 'Laravel Dasar',
            'penerbit'    => 'Gramedia',
            'pengarang'   => 'John Doe',
            'tahun_terbit'=> '2023-05-10',
            'cover_buku'  => 'laravel.png',
        ]);

        Buku::create([
            'kode_buku'   => 'BK002',
            'judul_buku'  => 'Belajar TailwindCSS',
            'penerbit'    => 'Erlangga',
            'pengarang'   => 'Jane Smith',
            'tahun_terbit'=> '2024-01-15',
            'cover_buku'  => 'tailwind.png',
        ]);

        Buku::create([
            'kode_buku'   => 'BK003',
            'judul_buku'  => 'Database MySQL',
            'penerbit'    => 'Informatika',
            'pengarang'   => 'Budi Santoso',
            'tahun_terbit'=> '2022-09-01',
            'cover_buku'  => 'mysql.png',
        ]);
    }
}
