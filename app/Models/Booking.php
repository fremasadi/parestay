<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = ['kost_id', 'kamar_id', 'user_id', 'tanggal_mulai', 'tanggal_selesai', 'durasi', 'total_harga', 'status'];

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

    public function Kamar()
    {
        return $this->belongsTo(Kamar::class);
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

    function penyewaLengkap($penyewa)
    {
        return $penyewa && $penyewa->no_ktp && $penyewa->foto_ktp && $penyewa->no_hp && $penyewa->alamat && $penyewa->pekerjaan;
    }

    /**
     * Get durasi dalam hari (untuk perhitungan jika diperlukan)
     */
    public function getDurasiDalamHariAttribute()
    {
        return match($this->durasi_type) {
            'harian' => $this->durasi,
            'mingguan' => $this->durasi * 7,
            'bulanan' => $this->durasi * 30, // Atau gunakan selisih tanggal aktual
            default => $this->durasi,
        };
    }

    /**
     * Get durasi dalam hari (versi akurat dari tanggal)
     */
    public function getDurasiAktualAttribute()
    {
        return $this->tanggal_mulai->diffInDays($this->tanggal_selesai);
    }

    /**
     * Get formatted durasi untuk display
     */
    public function getDurasiFormatAttribute()
    {
        $unit = match($this->durasi_type) {
            'harian' => 'hari',
            'mingguan' => 'minggu',
            'bulanan' => 'bulan',
            default => 'hari',
        };

        return "{$this->durasi} {$unit}";
    }
}
