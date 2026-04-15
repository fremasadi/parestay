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

    /**
     * Tanggal pertama yang benar-benar kosong (setelah semua booking aktif selesai).
     * Menangani booking berantai: Jan 1–15, Jan 15–28 → tersedia 28 Jan.
     */
    public function getNextAvailableDateAttribute(): \Carbon\Carbon
    {
        $bookings = $this->relationLoaded('bookings')
            ? $this->bookings->where('status', 'aktif')->sortBy('tanggal_mulai')
            : $this->bookings()->where('status', 'aktif')->orderBy('tanggal_mulai')->get();

        $date    = now()->startOfDay();
        $changed = true;

        while ($changed) {
            $changed = false;
            foreach ($bookings as $b) {
                if ($b->tanggal_mulai->lte($date) && $b->tanggal_selesai->gt($date)) {
                    $date    = $b->tanggal_selesai->copy()->startOfDay();
                    $changed = true;
                    break;
                }
            }
        }

        return $date;
    }
}