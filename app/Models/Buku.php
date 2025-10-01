<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Buku extends Model
{
    use HasFactory;

    // Nama tabel
    protected $table = 'buku';

    // Kolom yang bisa diisi
    protected $fillable = [
        'kode_buku',
        'judul_buku',
        'penerbit',
        'pengarang',
        'kategori_id',
        'tahun_terbit',
        'cover_buku',
    ];

    // Format otomatis untuk tahun terbit (biar gampang diakses)
    protected $casts = [
        'tahun_terbit' => 'date',
    ];
    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }
    public function stokHarga()
    {
        return $this->hasOne(StokHarga::class, 'buku_id');
    }
}
