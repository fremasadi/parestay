<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use App\Models\Booking;
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
     * Create Payment (Redirect dari Booking)
     */
    public function create($bookingId)
    {
        try {
            $booking = Booking::with(['kost', 'kamar', 'user'])->findOrFail($bookingId);

            // Validasi: Hanya pemilik booking yang bisa akses
            if ($booking->user_id !== auth()->id()) {
                return redirect()->route('booking.index')->with('error', 'Akses ditolak');
            }

            // ✅ PENTING: Cek apakah sudah ada payment untuk booking ini
            $existingPayment = Pembayaran::where('booking_id', $booking->id)->first();

            if ($existingPayment) {
                // Jika sudah ada payment (apapun statusnya), redirect ke show
                // JANGAN redirect ke create lagi!
                return redirect()->route('payment.show', $existingPayment->id);
            }

            // ✅ Generate Order ID yang unik
            $orderId = 'KOST-' . $booking->id . '-' . time();

            // Customer Details
            $customerDetails = [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->no_hp,
                'billing_address' => [
                    'address' => $booking->alamat,
                ],
            ];

            // Item Details dengan info kamar
            $itemName = 'Sewa Kost - ' . $booking->kost->nama;
            if ($booking->kamar) {
                $itemName .= ' (Kamar ' . $booking->kamar->nomor_kamar . ')';
            }
            $itemName .= ' - ' . $booking->durasi . ' ' . $booking->durasi_type;

            $itemDetails = [
                [
                    'id' => $booking->kamar_id ?? $booking->kost_id,
                    'price' => (int) $booking->total_harga,
                    'quantity' => 1,
                    'name' => $itemName,
                ],
            ];

            // ✅ Create transaction via Midtrans
            $result = $this->midtrans->createTransaction($orderId, (int) $booking->total_harga, $customerDetails, $itemDetails);

            if (!$result['success']) {
                Log::error('Midtrans Create Transaction Failed', [
                    'order_id' => $orderId,
                    'error' => $result['message'] ?? 'Unknown error',
                ]);

                return redirect()
                    ->route('booking.show', $booking->id)
                    ->with('error', 'Gagal membuat pembayaran: ' . ($result['message'] ?? 'Terjadi kesalahan'));
            }

            // ✅ Save payment to database
            $pembayaran = Pembayaran::create([
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'gross_amount' => $booking->total_harga,
                'transaction_status' => 'pending',
                'payment_url' => $result['payment_url'] ?? null,
                'midtrans_response' => json_encode($result),
            ]);

            Log::info('Payment Created Successfully', [
                'payment_id' => $pembayaran->id,
                'order_id' => $orderId,
                'booking_id' => $booking->id,
            ]);

            // ✅ Redirect ke halaman payment show (BUKAN create!)
            return redirect()->route('payment.show', $pembayaran->id)->with('success', 'Silakan selesaikan pembayaran Anda');
        } catch (\Exception $e) {
            Log::error('Error in PaymentController@create', [
                'booking_id' => $bookingId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()
                ->route('booking.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Show Payment Page
     */
    public function show($id)
    {
        try {
            $pembayaran = Pembayaran::with(['booking.kost', 'booking.kamar', 'booking.user'])->findOrFail($id);

            // Validasi: Hanya pemilik pembayaran yang bisa akses
            if ($pembayaran->booking->user_id !== auth()->id()) {
                return redirect()->route('booking.index')->with('error', 'Akses ditolak');
            }

            // ✅ HANYA update status jika masih pending
            if ($pembayaran->transaction_status === 'pending') {
                try {
                    $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);

                    if ($statusResult['success']) {
                        $this->updatePaymentStatus($pembayaran, $statusResult['data']);
                        $pembayaran->refresh();
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to get payment status', [
                        'order_id' => $pembayaran->order_id,
                        'error' => $e->getMessage(),
                    ]);
                    // Jangan redirect, tetap tampilkan halaman
                }
            }

            return view('payment.show', compact('pembayaran'));
        } catch (\Exception $e) {
            Log::error('Error in PaymentController@show', [
                'payment_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->route('booking.index')->with('error', 'Pembayaran tidak ditemukan');
        }
    }

    /**
     * Payment Callback from Midtrans
     */
    public function callback(Request $request)
    {
        $serverKey = config('midtrans.server_key');
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . $serverKey);

        if ($hashed !== $request->signature_key) {
            Log::warning('Invalid signature in payment callback', [
                'order_id' => $request->order_id,
            ]);
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $pembayaran = Pembayaran::where('order_id', $request->order_id)->first();

        if (!$pembayaran) {
            Log::warning('Payment not found in callback', [
                'order_id' => $request->order_id,
            ]);
            return response()->json(['message' => 'Payment not found'], 404);
        }

        $this->updatePaymentStatus($pembayaran, $request->all());

        return response()->json(['message' => 'Callback processed']);
    }

    /**
     * Payment Finish (User redirected here after payment)
     */
    public function finish(Request $request)
    {
        $orderId = $request->order_id;
        $pembayaran = Pembayaran::where('order_id', $orderId)->first();

        if (!$pembayaran) {
            return redirect()->route('landing')->with('error', 'Pembayaran tidak ditemukan');
        }

        // Get latest status
        $statusResult = $this->midtrans->getTransactionStatus($orderId);

        if ($statusResult['success']) {
            $this->updatePaymentStatus($pembayaran, $statusResult['data']);
        }

        return redirect()->route('payment.show', $pembayaran->id);
    }

    /**
     * Check Payment Status (AJAX)
     */
    public function checkStatus($id)
    {
        try {
            $pembayaran = Pembayaran::with('booking.kamar')->findOrFail($id);

            // Validasi akses
            if ($pembayaran->booking->user_id !== auth()->id()) {
                return response()->json(
                    [
                        'success' => false,
                        'message' => 'Akses ditolak',
                    ],
                    403,
                );
            }

            // Get latest status from Midtrans
            $statusResult = $this->midtrans->getTransactionStatus($pembayaran->order_id);

            Log::info('Check Payment Status', [
                'order_id' => $pembayaran->order_id,
                'current_status' => $pembayaran->transaction_status,
                'midtrans_result' => $statusResult,
            ]);

            if ($statusResult['success']) {
                $this->updatePaymentStatus($pembayaran, $statusResult['data']);

                // Refresh data setelah update
                $pembayaran->refresh();

                return response()->json([
                    'success' => true,
                    'status' => $pembayaran->transaction_status,
                    'message' => $pembayaran->getStatusLabel(),
                    'is_success' => $pembayaran->isSuccess(),
                ]);
            }

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Gagal mengecek status pembayaran: ' . ($statusResult['message'] ?? 'Unknown error'),
                ],
                500,
            );
        } catch (\Exception $e) {
            Log::error('Error checking payment status: ' . $e->getMessage());

            return response()->json(
                [
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                ],
                500,
            );
        }
    }

    /**
     * Update Payment Status
     */
    private function updatePaymentStatus($pembayaran, $data)
    {
        $transactionStatus = $data->transaction_status ?? ($data['transaction_status'] ?? 'pending');
        $fraudStatus = $data->fraud_status ?? ($data['fraud_status'] ?? null);
        $paymentType = $data->payment_type ?? ($data['payment_type'] ?? null);

        $updateData = [
            'transaction_id' => $data->transaction_id ?? ($data['transaction_id'] ?? null),
            'transaction_status' => $transactionStatus,
            'fraud_status' => $fraudStatus,
            'payment_type' => $paymentType,
            'midtrans_response' => is_array($data) ? $data : json_decode(json_encode($data), true),
        ];

        // Bank info for VA
        if (isset($data->va_numbers) || isset($data['va_numbers'])) {
            $vaNumbers = $data->va_numbers ?? $data['va_numbers'];
            if (!empty($vaNumbers)) {
                $vaNumber = is_array($vaNumbers) ? $vaNumbers[0] : $vaNumbers[0];
                $updateData['bank'] = $vaNumber->bank ?? ($vaNumber['bank'] ?? null);
                $updateData['va_number'] = $vaNumber->va_number ?? ($vaNumber['va_number'] ?? null);
            }
        }

        // Transaction time
        if (isset($data->transaction_time) || isset($data['transaction_time'])) {
            $updateData['transaction_time'] = $data->transaction_time ?? $data['transaction_time'];
        }

        // Settlement time & Update Booking/Kamar Status
        if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
            $updateData['settlement_time'] = now();

            // Update booking status menjadi aktif
            $pembayaran->booking->update([
                'status' => 'aktif',
            ]);

            // Update status kamar jika ada
            if ($pembayaran->booking->kamar) {
                $pembayaran->booking->kamar->update([
                    'status' => 'dibooking',
                ]);
            }

            // OPSIONAL: Kurangi slot tersedia di kost (jika masih pakai sistem slot)
            // $kost = $pembayaran->booking->kost;
            // if ($kost->slot_tersedia > 0) {
            //     $kost->decrement('slot_tersedia');
            // }
        }

        // Jika pembayaran gagal/expired/cancel
        if (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $pembayaran->booking->update([
                'status' => 'dibatalkan',
            ]);

            // Kembalikan status kamar jadi tersedia
            if ($pembayaran->booking->kamar) {
                $pembayaran->booking->kamar->update([
                    'status' => 'tersedia',
                ]);
            }
        }

        $pembayaran->update($updateData);
    }
}
