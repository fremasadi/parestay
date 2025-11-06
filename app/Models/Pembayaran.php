<?php
// app/Models/Pembayaran.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

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

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isPending()
    {
        return $this->transaction_status === 'pending';
    }

    public function isSuccess()
    {
        return in_array($this->transaction_status, ['settlement', 'capture']);
    }

    public function isFailed()
    {
        return in_array($this->transaction_status, ['deny', 'cancel', 'expire', 'failure']);
    }
}