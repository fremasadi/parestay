<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\User;
use App\Models\Kost;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil 3 kost terakhir dari database (sesuai yang kamu punya sekarang)
        $kosts = Kost::latest()->take(3)->get();

        // Ambil beberapa user dengan role penyewa (pastikan ada)
        $reviewers = User::where('role', 'penyewa')->inRandomOrder()->take(5)->get();

        if ($kosts->isEmpty() || $reviewers->isEmpty()) {
            $this->command->warn('⚠️ Tidak ada data kost atau user penyewa untuk membuat review.');
            return;
        }

        foreach ($kosts as $kost) {
            // Bikin 2 review per kost
            Review::create([
                'kost_id' => $kost->id,
                'reviewer_id' => $reviewers->random()->id,
                'rating' => rand(3, 5),
                'komentar' => 'Kost ini sangat nyaman dan sesuai dengan ekspektasi. Pemilik ramah dan lokasi strategis.',
            ]);

            Review::create([
                'kost_id' => $kost->id,
                'reviewer_id' => $reviewers->random()->id,
                'rating' => rand(3, 5),
                'komentar' => 'Fasilitas lengkap dan bersih. Cocok untuk tempat tinggal jangka panjang.',
            ]);
        }

        $this->command->info('✅ ReviewSeeder berhasil membuat review untuk ' . $kosts->count() . ' kost.');
    }
}
