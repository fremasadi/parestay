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
        Schema::table('pemiliks', function (Blueprint $table) {
            $table->string('nama_bank_2')->nullable()->after('atas_nama');
            $table->string('rekening_bank_2')->nullable()->after('nama_bank_2');
            $table->string('atas_nama_2')->nullable()->after('rekening_bank_2');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemiliks', function (Blueprint $table) {
            $table->dropColumn([
                'nama_bank_2',
                'rekening_bank_2',
                'atas_nama_2',
            ]);
        });
    }
};
