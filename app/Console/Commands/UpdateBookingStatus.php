<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Kamar;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    protected $signature   = 'booking:update-status';
    protected $description = 'Otomatis ubah status booking: aktif→selesai jika tanggal_selesai sudah lewat, '
                           . 'dan kembalikan kamar ke tersedia.';

    public function handle(): void
    {
        $today = now()->startOfDay();

        DB::transaction(function () use ($today) {

            // 1. Booking aktif yang tanggal_selesai-nya sudah lewat → selesai
            $selesai = Booking::where('status', 'aktif')
                ->whereDate('tanggal_selesai', '<=', $today)
                ->with('kamar')
                ->get();

            foreach ($selesai as $booking) {
                $booking->update(['status' => 'selesai']);

                // Kembalikan kamar ke tersedia jika tidak ada booking aktif lain hari ini
                if ($booking->kamar) {
                    $masihAktif = Booking::where('kamar_id', $booking->kamar_id)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $booking->id)
                        ->whereDate('tanggal_mulai', '<=', $today)
                        ->whereDate('tanggal_selesai', '>', $today)
                        ->exists();

                    if (!$masihAktif) {
                        $booking->kamar->update(['status' => 'tersedia']);
                    }
                }
            }

            // 2. Booking pending yang tanggal_mulai-nya sudah lewat → batalkan otomatis
            //    (user tidak pernah bayar sampai hari booking tiba)
            $kadaluarsa = Booking::where('status', 'pending')
                ->whereDate('tanggal_mulai', '<', $today)
                ->get();

            foreach ($kadaluarsa as $booking) {
                $booking->update(['status' => 'dibatalkan']);
            }

            Log::info('booking:update-status selesai', [
                'diselesaikan'  => $selesai->count(),
                'dikadaluarsa'  => $kadaluarsa->count(),
                'dijalankan_at' => now()->toDateTimeString(),
            ]);

            $this->info("Selesai: {$selesai->count()} booking → selesai, {$kadaluarsa->count()} booking pending kadaluarsa → dibatalkan.");
        });
    }
}
