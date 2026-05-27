<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'no_ktp',
        'foto_ktp',
        'no_hp',
        'alamat',
        'rekening_bank',
        'nama_bank',
        'atas_nama',
        'nama_bank_2',
        'rekening_bank_2',
        'atas_nama_2',
    ];

    /**
     * Relasi ke User (setiap pemilik terhubung ke 1 user)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Kost (jika nanti kamu buat tabel kosts)
     */
    public function kosts()
    {
        return $this->hasMany(Kost::class, 'owner_id');
    }
}
