<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class kategori extends Model
{
    protected $table = 'kategori';
    protected $fillable = ['kategori', 'jenis'];

    public function buku()
    {
        return $this->hasMany(Buku::class, 'id_buku');
    }
}
