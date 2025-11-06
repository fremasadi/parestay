<?php

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

    /**
     * Relationship: Booking belongs to Kost
     */
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    /**
     * Relationship: Booking belongs to User (Penyewa)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relationship: Booking has one Pembayaran
     */
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClass()
    {
        $classes = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'aktif' => 'bg-green-100 text-green-800',
            'selesai' => 'bg-blue-100 text-blue-800',
            'dibatalkan' => 'bg-red-100 text-red-800',
        ];

        return $classes[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get status label
     */
    public function getStatusLabel()
    {
        $labels = [
            'pending' => 'Menunggu Pembayaran',
            'aktif' => 'Aktif',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
        ];

        return $labels[$this->status] ?? 'Status Tidak Diketahui';
    }

    /**
     * Check if booking is active
     */
    public function isActive()
    {
        return $this->status === 'aktif';
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled()
    {
        return $this->status === 'pending';
    }

    /**
     * Format total harga
     */
    public function getFormattedTotalHargaAttribute()
    {
        return 'Rp ' . number_format($this->total_harga, 0, ',', '.');
    }
}