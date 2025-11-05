<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        

        // 🏠 Pemilik Kost
        User::create([
            'name' => 'Pemilik Kost A',
            'email' => 'pemilik@parestay.com',
            'password' => Hash::make('pemilik123'),
            'role' => 'pemilik',
            'status' => 'aktif',
        ]);

        // 👤 Penyewa
        User::create([
            'name' => 'Penyewa Pertama',
            'email' => 'penyewa@parestay.com',
            'password' => Hash::make('penyewa123'),
            'role' => 'penyewa',
            'status' => 'aktif',
        ]);
    }
}
