<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penarikan_dana', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pemilik_id')->constrained('pemiliks')->onDelete('cascade');
            $table->decimal('jumlah_bruto', 12, 2);  // total dari pembayaran settlement
            $table->decimal('biaya_admin', 12, 2);    // 2% dari jumlah_bruto
            $table->decimal('jumlah_bersih', 12, 2);  // jumlah_bruto - biaya_admin
            $table->string('rekening_tujuan')->nullable(); // no rekening pemilik
            $table->string('nama_bank')->nullable();
            $table->string('atas_nama')->nullable();
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->text('catatan')->nullable(); // catatan dari admin
            $table->timestamp('tanggal_pengajuan')->nullable();
            $table->timestamp('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('pemilik_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penarikan_dana');
    }
};
