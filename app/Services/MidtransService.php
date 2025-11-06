<?php
// app/Services/MidtransService.php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = config('midtrans.is_sanitized');
        Config::$is3ds = config('midtrans.is_3ds');
    }

    public function createTransaction($booking)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $this->generateOrderId($booking->id),
                'gross_amount' => (int) $booking->total_harga,
            ],
            'customer_details' => [
                'first_name' => $booking->user->name,
                'email' => $booking->user->email,
                'phone' => $booking->no_hp,
            ],
            'item_details' => [
                [
                    'id' => $booking->kost_id,
                    'price' => (int) $booking->total_harga,
                    'quantity' => 1,
                    'name' => 'Sewa ' . $booking->kost->nama . ' - ' . $booking->durasi . ' hari',
                ]
            ],
            'enabled_payments' => [
                'gopay', 'shopeepay', 'qris', 
                'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va',
                'echannel', // Mandiri Bill
            ],
        ];

        $snapToken = Snap::getSnapToken($params);
        
        return [
            'snap_token' => $snapToken,
            'order_id' => $params['transaction_details']['order_id'],
        ];
    }

    public function getTransactionStatus($orderId)
    {
        return Transaction::status($orderId);
    }

    private function generateOrderId($bookingId)
    {
        return 'BOOKING-' . $bookingId . '-' . time();
    }
}