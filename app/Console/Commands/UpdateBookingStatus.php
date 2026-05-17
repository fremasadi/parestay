<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Pembayaran;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateBookingStatus extends Command
{
    protected $signature = 'booking:update-status';

    protected $description = 'Otomatis update status booking, membatalkan pembayaran pending > 24 jam, dan mengembalikan kamar tersedia.';

    public function handle(): void
    {
        $today = now()->startOfDay();
        $batasPembayaran = now()->subDay();

        DB::transaction(function () use ($today, $batasPembayaran) {
            // 1. Booking aktif yang tanggal_selesai-nya sudah lewat -> selesai
            $selesai = Booking::where('status', 'aktif')
                ->whereDate('tanggal_selesai', '<=', $today)
                ->with('kamar')
                ->get();

            foreach ($selesai as $booking) {
                $booking->update(['status' => 'selesai']);

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

            // 2. Booking pending yang tanggal_mulai-nya sudah lewat -> batalkan otomatis
            $kadaluarsa = Booking::where('status', 'pending')
                ->whereDate('tanggal_mulai', '<', $today)
                ->get();

            foreach ($kadaluarsa as $booking) {
                $booking->update(['status' => 'dibatalkan']);
            }

            // 3. Pembayaran pending lebih dari 24 jam -> expire + booking dibatalkan
            $pembayaranKadaluarsa = Pembayaran::with('booking.kamar')
                ->where('transaction_status', 'pending')
                ->where('created_at', '<=', $batasPembayaran)
                ->get();

            foreach ($pembayaranKadaluarsa as $pembayaran) {
                $booking = $pembayaran->booking;

                $pembayaran->update([
                    'transaction_status' => 'expire',
                    'notes' => 'Otomatis kadaluarsa karena belum dibayar dalam 24 jam.',
                ]);

                if ($booking && $booking->status === 'pending') {
                    $booking->update(['status' => 'dibatalkan']);
                }

                if ($booking?->kamar && $booking->kamar->status === 'dibooking') {
                    $masihAdaAktif = Booking::where('kamar_id', $booking->kamar_id)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $booking->id)
                        ->exists();

                    if (!$masihAdaAktif) {
                        $booking->kamar->update(['status' => 'tersedia']);
                    }
                }
            }

            Log::info('booking:update-status selesai', [
                'diselesaikan' => $selesai->count(),
                'dikadaluarsa' => $kadaluarsa->count(),
                'pembayaran_kadaluarsa' => $pembayaranKadaluarsa->count(),
                'dijalankan_at' => now()->toDateTimeString(),
            ]);

            $this->info(
                "Selesai: {$selesai->count()} booking -> selesai, "
                . "{$kadaluarsa->count()} booking pending lewat tanggal mulai -> dibatalkan, "
                . "{$pembayaranKadaluarsa->count()} pembayaran pending > 24 jam -> expire."
            );
        });
    }
}
