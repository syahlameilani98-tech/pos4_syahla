<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->decimal('uang_masuk', 15, 2)->default(0)->after('total_pembayaran');
            $table->decimal('kembalian', 15, 2)->default(0)->after('uang_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('penjualan', function (Blueprint $table) {
            $table->dropColumn(['uang_masuk', 'kembalian']);
        });
    }
};