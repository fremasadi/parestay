<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'kost_id',
        'reviewer_id',
        'rating',
        'komentar',
        'balasan_pemilik',
        'balasan_pemilik_at',
    ];

    protected $casts = [
        'rating' => 'integer',
        'balasan_pemilik_at' => 'datetime',
    ];

    // Relasi ke Kost
    public function kost()
    {
        return $this->belongsTo(Kost::class);
    }

    // Relasi ke User (Reviewer)
    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    // Helper untuk mendapatkan bintang rating
    public function getStarsAttribute()
    {
        return str_repeat('⭐', $this->rating);
    }
}
