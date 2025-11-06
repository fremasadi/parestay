<?php
// app/Http/Controllers/PaymentController.php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtrans;

    public function __construct(MidtransService $midtrans)
    {
        $this->midtrans = $midtrans;
    }

    /**
     * Callback dari Midtrans
     */
    public function callback(Request $request)
    {
        try {
            $notification = $request->all();
            
            Log::info('Midtrans Callback', $notification);

            $orderId = $notification['order_id'];
            $transactionStatus = $notification['transaction_status'];
            $fraudStatus = $notification['fraud_status'] ?? null;

            $pembayaran = Pembayaran::where('order_id', $orderId)->firstOrFail();

            DB::beginTransaction();

            // Update data pembayaran
            $pembayaran->update([
                'transaction_id' => $notification['transaction_id'] ?? null,
                'payment_type' => $notification['payment_type'] ?? null,
                'transaction_status' => $transactionStatus,
                'fraud_status' => $fraudStatus,
                'transaction_time' => $notification['transaction_time'] ?? now(),
                'settlement_time' => $notification['settlement_time'] ?? null,
                'bank' => $notification['bank'] ?? $notification['va_numbers'][0]['bank'] ?? null,
                'va_number' => $notification['va_numbers'][0]['va_number'] ?? null,
                'midtrans_response' => $notification,
            ]);

            $booking = $pembayaran->booking;

            // Update status booking berdasarkan status transaksi
            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'accept') {
                    $booking->update(['status' => 'aktif']);
                }
            } elseif ($transactionStatus == 'settlement') {
                $booking->update(['status' => 'aktif']);
                $pembayaran->update(['settlement_time' => now()]);
            } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $booking->update(['status' => 'dibatalkan']);
                // Kembalikan slot kost
                $booking->kost->increment('slot_tersedia');
            } elseif ($transactionStatus == 'pending') {
                $booking->update(['status' => 'pending']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Notification processed'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Midtrans Callback Error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Finish payment - redirect dari Midtrans
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return redirect()->route('home')->with('error', 'Pembayaran tidak ditemukan.');
        }

        // Cek status terbaru dari Midtrans
        try {
            $status = $this->midtrans->getTransactionStatus($orderId);
            
            $pembayaran->update([
                'transaction_status' => $status->transaction_status,
                'fraud_status' => $status->fraud_status ?? null,
            ]);

            if (in_array($status->transaction_status, ['settlement', 'capture'])) {
                $pembayaran->booking->update(['status' => 'aktif']);
            }

        } catch (\Exception $e) {
            Log::error('Get Transaction Status Error: ' . $e->getMessage());
        }

        return redirect()->route('booking.show', $pembayaran->booking_id)
            ->with('success', 'Terima kasih! Pembayaran Anda sedang diproses.');
    }

    /**
     * Unfinish payment - user kembali tanpa menyelesaikan
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if ($pembayaran) {
            return redirect()->route('booking.payment', $pembayaran->booking_id)
                ->with('warning', 'Pembayaran belum selesai. Silakan lanjutkan pembayaran.');
        }

        return redirect()->route('home');
    }

    /**
     * Error payment
     */
    public function error(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if ($pembayaran) {
            return redirect()->route('booking.payment', $pembayaran->booking_id)
                ->with('error', 'Terjadi kesalahan dalam proses pembayaran. Silakan coba lagi.');
        }

        return redirect()->route('home')
            ->with('error', 'Terjadi kesalahan dalam proses pembayaran.');
    }

    /**
     * Check payment status
     */
    public function checkStatus($orderId)
    {
        try {
            $pembayaran = Pembayaran::where('order_id', $orderId)->firstOrFail();
            
            // Get latest status from Midtrans
            $status = $this->midtrans->getTransactionStatus($orderId);
            
            return response()->json([
                'success' => true,
                'status' => $status->transaction_status,
                'payment_type' => $status->payment_type ?? null,
                'va_number' => $status->va_numbers[0]->va_number ?? null,
                'bank' => $status->va_numbers[0]->bank ?? null,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}