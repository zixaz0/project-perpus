<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StokHarga extends Model
{
    use HasFactory;

    protected $table = 'stok_harga';

    protected $fillable = [
        'buku_id',
        'stok',
        'harga',
    ];

    public function buku()
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }
}
