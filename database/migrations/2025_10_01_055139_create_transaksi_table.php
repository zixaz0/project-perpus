<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kasir_id')->constrained('users')->onDelete('cascade');
            $table->decimal('total_harga', 12, 2);
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('subtotal', 12, 2);
            $table->decimal('dibayar', 12, 2)->nullable();
            $table->decimal('kembalian', 12, 2)->nullable();
            $table->enum('metode_bayar', ['cash','qris','debit','credit'])->default('cash');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('transaksi');
    }
};
