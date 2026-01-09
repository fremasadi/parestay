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
}