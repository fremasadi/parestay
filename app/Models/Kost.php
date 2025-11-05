<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Casts\JsonArray;

class Kost extends Model
{
    use HasFactory;

    protected $table = 'kosts';

    protected $fillable = [
        'owner_id',
        'nama',
        'harga',
        'type_harga',
        'alamat',
        'latitude',
        'longitude',
        'jenis_kost',
        'fasilitas',
        'peraturan',
        'images',
        'total_slot',
        'slot_tersedia',
        'status',
        'terverifikasi',
    ];

     protected $casts = [
        'fasilitas' => 'json',      
        'peraturan' => 'json',      
        'images' => JsonArray::class,
        'terverifikasi' => 'boolean',
        'harga' => 'decimal:0',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function getFasilitasAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            // Jika masih string setelah decode pertama, decode lagi
            if (is_string($decoded)) {
                return json_decode($decoded, true) ?? [];
            }
            return $decoded ?? [];
        }
        return $value ?? [];
    }

    // 🔧 FIX: Accessor untuk peraturan (double-encoded)
    public function getPeraturanAttribute($value)
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            if (is_string($decoded)) {
                return json_decode($decoded, true) ?? [];
            }
            return $decoded ?? [];
        }
        return $value ?? [];
    }

    // 🔧 FIX: Accessor untuk images
    public function getImagesAttribute($value)
    {
        if (is_string($value)) {
            return json_decode($value, true) ?? [];
        }
        return $value ?? [];
    }

    /** 🔗 Relasi: Kost dimiliki oleh Pemilik */
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'owner_id');
    }

    /** 🔗 Kost memiliki banyak review */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'kost_id');
    }

    /** 🔗 Kost bisa memiliki banyak booking */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'kost_id');
    }
    
}
