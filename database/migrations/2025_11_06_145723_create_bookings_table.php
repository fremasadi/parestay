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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kost_id')->constrained('kosts')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade')->comment('penyewa');
            $table->string('no_ktp', 20);
            $table->string('foto_ktp')->nullable();
            $table->string('no_hp', 15);
            $table->text('alamat');
            $table->string('pekerjaan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('durasi')->comment('dalam hari');
            $table->decimal('total_harga', 15, 2);
            $table->enum('status', ['pending', 'aktif', 'selesai', 'dibatalkan'])->default('pending');
            $table->timestamps();
            
            // Indexes
            $table->index('kost_id');
            $table->index('user_id');
            $table->index('status');
            $table->index('tanggal_mulai');
            $table->index('tanggal_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
