<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_harga', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buku_id')
                  ->constrained('buku')
                  ->onDelete('cascade'); // kalau buku dihapus, stok_harga ikut hilang
            $table->integer('stok')->default(0);
            $table->decimal('harga', 15, 2); // format harga, maksimal 15 digit, 2 desimal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_harga');
    }
};