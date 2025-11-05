<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('kosts', function (Blueprint $table) {
            //
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kosts', function (Blueprint $table) {
            // $table->renameColumn('harga_per_bulan', 'harga');

            // ➕ Tambahkan kolom type_harga
            $table->enum('type_harga', ['harian', 'mingguan', 'bulanan'])
                ->default('bulanan')
                ->after('harga');
        });
    }
};
