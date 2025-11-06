<?php
// app/Models/Booking.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'user_id',
        'no_ktp',
        'foto_ktp',
        'no_hp',
        'alamat',
        'pekerjaan',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi',
        'total_harga',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'total_harga' => 'decimal:2',
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function hasPembayaran()
    {
        return $this->pembayaran()->exists();
    }

    public function isPaid()
    {
        return $this->hasPembayaran() && $this->pembayaran->isSuccess();
    }
}