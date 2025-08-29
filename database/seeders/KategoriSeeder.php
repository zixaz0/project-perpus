<?php

namespace Database\Seeders;

use App\Models\kategori;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KategoriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Kategori::create([
            'kategori' => 'Novel',
            'jenis' => 'Fiksi'
        ]);
        Kategori::create([
            'kategori' => 'Cecep',
            'jenis' => 'Pemadam'
        ]);
        Kategori::create([
            'kategori' => 'Non Fiksi',
            'jenis' => 'Aduy'
        ]);
    }
}
