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
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();

            $table->foreignId('kost_id')
                  ->constrained('kosts')
                  ->cascadeOnDelete();

            $table->string('nomor_kamar');
            $table->unsignedInteger('harga');
            $table->enum('type_harga', ['harian', 'bulanan', 'tahunan']);
            $table->string('luas_kamar')->nullable();
            $table->json('fasilitas')->nullable();
            $table->json('images')->nullable();
            $table->enum('status', ['tersedia', 'dibooking', 'nonaktif'])
                  ->default('tersedia');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};
