<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\JsonArray;
use App\Casts\JsonStringArray;

class Kamar extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'nomor_kamar',
        'harga',
        'type_harga',
        'luas_kamar',
        'fasilitas',
        'images',
        'status',
    ];

    protected $casts = [
        'fasilitas' => JsonStringArray::class,
        'images' => JsonArray::class,
    ];

    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Apakah kamar sedang ditempati HARI INI (ada booking aktif yang mencakup hari ini).
     */
    public function isOccupiedNow(): bool
    {
        return $this->bookings()
            ->where('status', 'aktif')
            ->whereDate('tanggal_mulai', '<=', today())
            ->whereDate('tanggal_selesai', '>', today())
            ->exists();
    }

    /**
     * Apakah kamar tersedia untuk rentang tanggal [mulai, selesai)?
     * Digunakan saat validasi store booking.
     */
    public function isAvailableForDates($mulai, $selesai): bool
    {
        return !$this->bookings()
            ->where('status', 'aktif')
            ->where('tanggal_mulai', '<', $selesai)
            ->where('tanggal_selesai', '>', $mulai)
            ->exists();
    }

    /**
     * Accessor untuk cek tersedia hari ini.
     * Jika relasi bookings sudah di-eager-load, pakai collection (hemat query).
     */
    public function getIsAvailableNowAttribute(): bool
    {
        if ($this->relationLoaded('bookings')) {
            $today = now();
            return !$this->bookings->contains(fn($b) =>
                $b->status === 'aktif'
                && $b->tanggal_mulai->lte($today)
                && $b->tanggal_selesai->gt($today)
            );
        }
        return !$this->isOccupiedNow();
    }
}