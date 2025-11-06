<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayarans';

    protected $fillable = [
        'booking_id',
        'order_id',
        'transaction_id',
        'gross_amount',
        'payment_type',
        'bank',
        'va_number',
        'transaction_status',
        'fraud_status',
        'transaction_time',
        'settlement_time',
        'payment_url',
        'midtrans_response',
        'notes',
    ];

    protected $casts = [
        'gross_amount' => 'decimal:2',
        'midtrans_response' => 'array',
        'transaction_time' => 'datetime',
        'settlement_time' => 'datetime',
    ];

    /**
     * Relationship: Pembayaran belongs to Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Check if payment is successful (settlement or capture)
     */
    public function isSuccess()
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    /**
     * Check if payment is pending
     */
    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    /**
     * Check if payment is failed
     */
    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'expire', 'cancel', 'failure']);
    }

    /**
     * Get readable status label
     */
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'settlement' => 'Pembayaran Berhasil',
            'capture' => 'Pembayaran Berhasil',
            'deny' => 'Pembayaran Ditolak',
            'expire' => 'Pembayaran Kadaluarsa',
            'cancel' => 'Pembayaran Dibatalkan',
            'failure' => 'Pembayaran Gagal',
        ];

        return $labels[$this->transaction_status] ?? 'Status Tidak Diketahui';
    }

    /**
     * Get status badge color class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'settlement' => 'bg-green-100 text-green-800',
            'capture' => 'bg-green-100 text-green-800',
            'deny' => 'bg-red-100 text-red-800',
            'expire' => 'bg-gray-100 text-gray-800',
            'cancel' => 'bg-red-100 text-red-800',
            'failure' => 'bg-red-100 text-red-800',
        ];

        return $classes[$this->transaction_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get payment method label
     */
    public function getPaymentMethodLabel()
    {
        if (!$this->payment_type) {
            return '-';
        }

        $labels = [
            'credit_card' => 'Kartu Kredit',
            'bank_transfer' => strtoupper($this->bank ?? '') . ' Virtual Account',
            'echannel' => 'Mandiri Bill',
            'gopay' => 'GoPay',
            'qris' => 'QRIS',
            'shopeepay' => 'ShopeePay',
            'other' => 'Lainnya',
        ];

        return $labels[$this->payment_type] ?? ucfirst($this->payment_type);
    }

    /**
     * Format gross amount with currency
     */
    public function getFormattedAmountAttribute()
    {
        return 'Rp ' . number_format($this->gross_amount, 0, ',', '.');
    }
}