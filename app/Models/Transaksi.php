<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $table = 'transaksi';
    
    protected $fillable = [
        'kasir_id',
        'total_harga',
        'diskon',
        'subtotal',
        'dibayar',
        'kembalian',
        'metode_bayar',
        'status',
        'refund_at',
        'refund_by'
    ];

    // refund_at jadi Carbon instance
    protected $casts = [
        'refund_at' => 'datetime',
    ];

    // Relasi ke kasir
    public function kasir() {
        return $this->belongsTo(User::class, 'kasir_id');
    }

    // Relasi ke item transaksi
    public function items() {
        return $this->hasMany(TransaksiItem::class);
    }

    public function refundBy() {
        return $this->belongsTo(User::class, 'refund_by');
    }
}