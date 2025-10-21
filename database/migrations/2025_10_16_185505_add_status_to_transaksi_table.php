// database/migrations/xxxx_add_status_to_transaksi_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->enum('status', ['selesai', 'refund'])->default('selesai')->after('metode_bayar');
            $table->timestamp('refund_at')->nullable()->after('status');
            $table->unsignedBigInteger('refund_by')->nullable()->after('refund_at');
            
            $table->foreign('refund_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transaksi', function (Blueprint $table) {
            $table->dropForeign(['refund_by']);
            $table->dropColumn(['status', 'refund_at', 'refund_by']);
        });
    }
};