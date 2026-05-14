<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->text('balasan_pemilik')->nullable()->after('komentar');
            $table->timestamp('balasan_pemilik_at')->nullable()->after('balasan_pemilik');
        });
    }

    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropColumn(['balasan_pemilik', 'balasan_pemilik_at']);
        });
    }
};
