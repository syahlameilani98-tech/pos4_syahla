<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->string('kode_jenis')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('jenis', function (Blueprint $table) {
            $table->string('kode_jenis')->nullable(false)->change();
        });
    }
};