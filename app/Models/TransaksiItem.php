<?php

namespace App\Models;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransaksiItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaksi_id', 'buku_id', 'qty', 'harga_satuan', 'subtotal'
    ];

    // Relasi ke transaksi
    public function transaksi() {
        return $this->belongsTo(Transaksi::class);
    }

    // Relasi ke buku
    public function buku() {
        return $this->belongsTo(Buku::class);
    }
}