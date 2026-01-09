<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemilik extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'no_ktp', 'no_hp', 'alamat', 'rekening_bank', 'nama_bank', 'atas_nama'];

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
