<?php

namespace Database\Seeders;

use App\Models\Kost;
use App\Models\Pemilik;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class KostSeeder extends Seeder
{
    public function run(): void
    {
        $pemiliks = Pemilik::all();

        $kosts = [
            [
                'nama' => 'Kost Mawar Residence',
                'harga_per_bulan' => 1500000,
                'alamat' => 'Jl. Mawar No. 10, Kebayoran Baru, Jakarta Selatan',
                'latitude' => -6.242501,
                'longitude' => 106.797190,
                'jenis_kost' => 'putri',
                'fasilitas' => json_encode(['AC', 'Kasur', 'Lemari / Storage', 'K. Mandi Dalam', 'WiFi']),
                'peraturan' => json_encode(['Tamu menginap dikenakan biaya', 'Tidak boleh bawa anak']),
                'total_slot' => 10,
                'slot_tersedia' => 3,
                'status' => 'tersedia',
                'terverifikasi' => true,
            ],
            [
                'nama' => 'Kost Melati Indah',
                'harga_per_bulan' => 1200000,
                'alamat' => 'Jl. Melati Raya No. 25, Tebet, Jakarta Selatan',
                'latitude' => -6.227700,
                'longitude' => 106.857199,
                'jenis_kost' => 'bebas',
                'fasilitas' => json_encode(['Kasur', 'Meja', 'Kursi', 'K. Mandi Luar', 'WiFi']),
                'peraturan' => json_encode(['Tipe ini bisa diisi maks. 2 orang/ kamar', 'Tidak untuk pasutri']),
                'total_slot' => 15,
                'slot_tersedia' => 7,
                'status' => 'tersedia',
                'terverifikasi' => true,
            ],
            // ... (kost lain tetap sama)
        ];

        foreach ($kosts as $index => $kostData) {
            $kostData['owner_id'] = $pemiliks[$index % $pemiliks->count()]->id;

            // ✅ Semua kost punya gambar yang sama
            $kostData['images'] = [
                "kost-images/26ZS8yl9kFhtNsdtBSppnGbdSedwT9n8WKhoVVDo.jpg",
                "kost-images/H7BfGS6SwoUFPOrBTjJ1HpzErV94jOQzL1QjckRk.jpg"
            ];

            Kost::create($kostData);
        }
    }
}
