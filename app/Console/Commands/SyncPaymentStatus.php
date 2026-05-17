<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Pembayaran;
use App\Services\MidtransService;
use App\Services\OwnerPaymentNotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SyncPaymentStatus extends Command
{
    protected $signature = 'payment:sync-status';

    protected $description = 'Cek pembayaran pending ke Midtrans, update status lokal, lalu kirim WA ke pemilik jika pembayaran berhasil.';

    public function __construct(
        protected MidtransService $midtrans,
        protected OwnerPaymentNotificationService $ownerNotification,
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $pembayarans = Pembayaran::with('booking.kamar')
            ->where('transaction_status', 'pending')
            ->get();

        $berubah = 0;
        $notifikasiTerkirim = 0;
        $gagalDicek = 0;
        $bookingDipulihkan = 0;

        foreach ($pembayarans as $pembayaran) {
            $result = $this->midtrans->getTransactionStatus($pembayaran->order_id);

            if (!$result['success']) {
                $gagalDicek++;
                Log::warning('payment:sync-status gagal cek ke Midtrans', [
                    'pembayaran_id' => $pembayaran->id,
                    'order_id' => $pembayaran->order_id,
                    'message' => $result['message'] ?? 'Unknown error',
                ]);
                continue;
            }

            $data = $result['data'];
            $statusBaru = $data->transaction_status ?? ($data['transaction_status'] ?? 'pending');
            $statusLama = $pembayaran->transaction_status;

            $updateData = [
                'transaction_id' => $data->transaction_id ?? ($data['transaction_id'] ?? null),
                'transaction_status' => $statusBaru,
                'fraud_status' => $data->fraud_status ?? ($data['fraud_status'] ?? null),
                'payment_type' => $data->payment_type ?? ($data['payment_type'] ?? null),
                'midtrans_response' => json_decode(json_encode($data), true),
            ];

            if (isset($data->transaction_time) || isset($data['transaction_time'])) {
                $updateData['transaction_time'] = $data->transaction_time ?? $data['transaction_time'];
            }

            if (isset($data->va_numbers) || isset($data['va_numbers'])) {
                $vaNumbers = $data->va_numbers ?? $data['va_numbers'];

                if (!empty($vaNumbers)) {
                    $vaNumber = is_array($vaNumbers) ? $vaNumbers[0] : $vaNumbers[0];
                    $updateData['bank'] = $vaNumber->bank ?? ($vaNumber['bank'] ?? null);
                    $updateData['va_number'] = $vaNumber->va_number ?? ($vaNumber['va_number'] ?? null);
                }
            }

            if (in_array($statusBaru, ['settlement', 'capture'])) {
                $updateData['settlement_time'] = now();
                $booking = $pembayaran->booking;

                if ($booking && $booking->status === 'pending') {
                    $adaOverlapAktif = Booking::where('kamar_id', $booking->kamar_id)
                        ->where('status', 'aktif')
                        ->where('id', '!=', $booking->id)
                        ->where('tanggal_mulai', '<', $booking->tanggal_selesai)
                        ->where('tanggal_selesai', '>', $booking->tanggal_mulai)
                        ->exists();

                    if ($adaOverlapAktif) {
                        $booking->update(['status' => 'dibatalkan']);
                        $updateData['notes'] = 'Otomatis dibatalkan: kamar sudah diambil penyewa lain yang lebih dulu bayar.';
                    } else {
                        $booking->update(['status' => 'aktif']);

                        if ($booking->kamar) {
                            $booking->kamar->update(['status' => 'dibooking']);
                        }

                        Booking::where('kamar_id', $booking->kamar_id)
                            ->where('status', 'pending')
                            ->where('id', '!=', $booking->id)
                            ->where('tanggal_mulai', '<', $booking->tanggal_selesai)
                            ->where('tanggal_selesai', '>', $booking->tanggal_mulai)
                            ->update(['status' => 'dibatalkan']);
                    }
                }
            }

            if (in_array($statusBaru, ['deny', 'expire', 'cancel', 'failure'])) {
                $pembayaran->booking?->update(['status' => 'dibatalkan']);
            }

            $pembayaran->update($updateData);
            $pembayaran->refresh();

            if ($statusBaru !== $statusLama) {
                $berubah++;
            }

            if ($this->ownerNotification->sendIfNeeded($pembayaran)) {
                $notifikasiTerkirim++;
            }
        }

        $pembayaranSuksesDibatalkan = Pembayaran::with('booking.kamar')
            ->whereIn('transaction_status', ['settlement', 'capture'])
            ->whereHas('booking', fn ($query) => $query->where('status', 'dibatalkan'))
            ->get();

        foreach ($pembayaranSuksesDibatalkan as $pembayaran) {
            $booking = $pembayaran->booking;

            if (!$booking) {
                continue;
            }

            $adaOverlapAktif = Booking::where('kamar_id', $booking->kamar_id)
                ->where('status', 'aktif')
                ->where('id', '!=', $booking->id)
                ->where('tanggal_mulai', '<', $booking->tanggal_selesai)
                ->where('tanggal_selesai', '>', $booking->tanggal_mulai)
                ->exists();

            if (!$adaOverlapAktif) {
                $booking->update(['status' => 'aktif']);

                if ($booking->kamar) {
                    $booking->kamar->update(['status' => 'dibooking']);
                }

                $bookingDipulihkan++;
            }
        }

        $this->info(
            "Selesai sync: {$pembayarans->count()} dicek, "
            . "{$berubah} berubah status, "
            . "{$notifikasiTerkirim} notifikasi terkirim, "
            . "{$gagalDicek} gagal dicek, "
            . "{$bookingDipulihkan} booking sukses dipulihkan."
        );

        return self::SUCCESS;
    }
}
