<?php

namespace Database\Seeders;

use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Database\Seeder;

class PemilikSeeder extends Seeder
{
    public function run(): void
    {
        // Ambil user yang bukan admin (id > 1)
        $users = User::where('id', '>', 1)->where('id', '<=', 6)->get();

        $pemiliks = [
            [
                'no_ktp' => '3174012801900001',
                'no_hp' => '081234567890',
                'alamat' => 'Jl. Sudirman No. 123, Jakarta Selatan',
                'rekening_bank' => '1234567890',
                'nama_bank' => 'BCA',
                'atas_nama' => 'Budi Santoso',
            ],
            [
                'no_ktp' => '3174015505920002',
                'no_hp' => '081298765432',
                'alamat' => 'Jl. Thamrin No. 45, Jakarta Pusat',
                'rekening_bank' => '9876543210',
                'nama_bank' => 'Mandiri',
                'atas_nama' => 'Siti Nurhaliza',
            ],
            [
                'no_ktp' => '3174011010850003',
                'no_hp' => '081312345678',
                'alamat' => 'Jl. Gatot Subroto No. 67, Jakarta Selatan',
                'rekening_bank' => '5678901234',
                'nama_bank' => 'BNI',
                'atas_nama' => 'Agus Wijaya',
            ],
            [
                'no_ktp' => '3174012202880004',
                'no_hp' => '081387654321',
                'alamat' => 'Jl. HR Rasuna Said No. 89, Jakarta Selatan',
                'rekening_bank' => '4321098765',
                'nama_bank' => 'BRI',
                'atas_nama' => 'Dewi Lestari',
            ],
            [
                'no_ktp' => '3174013103910005',
                'no_hp' => '081456789012',
                'alamat' => 'Jl. Kuningan Barat No. 21, Jakarta Selatan',
                'rekening_bank' => '6789012345',
                'nama_bank' => 'CIMB Niaga',
                'atas_nama' => 'Rudi Hartono',
            ],
        ];

        foreach ($users as $index => $user) {
            Pemilik::create(array_merge(
                ['user_id' => $user->id],
                $pemiliks[$index]
            ));
        }
    }
}