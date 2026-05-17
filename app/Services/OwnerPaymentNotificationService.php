<?php

namespace App\Services;

use App\Models\Pembayaran;
use Illuminate\Support\Facades\Log;

class OwnerPaymentNotificationService
{
    public function __construct(
        protected FonnteService $fonnte,
    ) {
    }

    public function sendIfNeeded(Pembayaran $pembayaran): bool
    {
        $pembayaran->loadMissing('booking.kost.pemilik');

        if (!$pembayaran->isSuccess() || $pembayaran->owner_notified_at) {
            return false;
        }

        $booking = $pembayaran->booking;
        $kost = $booking?->kost;
        $pemilik = $kost?->pemilik;

        if (!$pemilik?->no_hp) {
            Log::warning('Nomor HP pemilik tidak tersedia untuk notifikasi pembayaran', [
                'pembayaran_id' => $pembayaran->id,
                'booking_id' => $booking?->id,
                'kost_id' => $kost?->id,
            ]);

            return false;
        }

        $message = "Pembayaran berhasil diterima.\n"
            . "Kost: {$kost->nama}\n"
            . "Booking ID: {$booking->id}\n"
            . "Total: Rp " . number_format((float) $pembayaran->gross_amount, 0, ',', '.') . "\n"
            . "Status booking sekarang: aktif.";

        $sent = $this->fonnte->sendMessage($pemilik->no_hp, $message);

        if ($sent) {
            $pembayaran->update([
                'owner_notified_at' => now(),
            ]);
        }

        Log::info('Notifikasi pembayaran ke pemilik diproses', [
            'pembayaran_id' => $pembayaran->id,
            'booking_id' => $booking->id,
            'pemilik_id' => $pemilik->id,
            'sent' => $sent,
        ]);

        return $sent;
    }
}
