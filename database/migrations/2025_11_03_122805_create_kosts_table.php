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
        Schema::create('kosts', function (Blueprint $table) {
            $table->id();

            // Relasi ke pemilik
            $table->foreignId('owner_id')
                ->constrained('pemiliks')
                ->onDelete('cascade');

            // Data utama kost
            $table->string('nama');
            $table->decimal('harga_per_bulan', 12, 2)->default(0);
            $table->string('alamat');
            $table->float('latitude', 10, 6)->nullable();
            $table->float('longitude', 10, 6)->nullable();
            $table->enum('jenis_kost', ['putra', 'putri', 'bebas'])->default('bebas');

            $table->json('fasilitas')->nullable();
            $table->json('peraturan')->nullable();

            // Slot / kapasitas
            $table->integer('total_slot')->default(0);
            $table->integer('slot_tersedia')->default(0);

            // Status kost
            $table->enum('status', ['tersedia', 'penuh', 'menunggu'])->default('menunggu');

            // Apakah kost sudah diverifikasi admin
            $table->boolean('terverifikasi')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kosts');
    }
};
