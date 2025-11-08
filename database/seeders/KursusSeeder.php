<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kursus;

class KursusSeeder extends Seeder
{
    public function run(): void
    {
        $kursuses = [
            [
                'nama' => 'Titik Nol English Course',
                'alamat' => 'Jl. Brawijaya No.75, Mangunrejo, Tulungrejo, Kec. Pare, Kabupaten Kediri, Jawa Timur 64212',
                'latitude' => -7.7566633482563985,
                'longitude' => 112.18191978103843,
            ],
            [
                'nama' => 'ACCESS English School',
                'alamat' => 'Jl. Dahlia No.26, Mangunrejo, Tulungrejo, Kec. Pare, Kabupaten Kediri, Jawa Timur 64212',
                'latitude' => -7.755678501180482,
                'longitude' => 112.18551833686156,
            ],
            [
                'nama' => 'Kampung Inggris EM',
                'alamat' => 'Jl. Flamboyan No.12B, RT.8/RW.8, Mulyoasri, Tulungrejo, Kec. Pare, Kabupaten Kediri, Jawa Timur 64212',
                'latitude' => -7.759198327203594,
                'longitude' => 112.19628685035558,
            ],
            [
                'nama' => 'Kampung Inggris LC',
                'alamat' => 'Jl. Langkat No.88, Singgahan, Pelem, Kec. Pare, Kabupaten Kediri, Jawa Timur 64213',
                'latitude' => -7.749596975356832,
                'longitude' => 112.17543875035538,
            ],
            [
                'nama' => 'Kampung Inggris BBC - Bisa Bahasa Center',
                'alamat' => 'Jl.Kemuning, Mangunrejo, Tulungrejo, Kec. Pare, Kabupaten Kediri, Jawa Timur 64212',
                'latitude' => -7.756846679012622,
                'longitude' => 112.18073420802627,
            ],
        ];

        foreach ($kursuses as $kursus) {
            Kursus::create($kursus);
        }
    }
}
