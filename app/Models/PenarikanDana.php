<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenarikanDana extends Model
{
    use HasFactory;

    protected $table = 'penarikan_dana';

    protected $fillable = [
        'pemilik_id',
        'jumlah_bruto',
        'biaya_admin',
        'jumlah_bersih',
        'rekening_tujuan',
        'nama_bank',
        'atas_nama',
        'status',
        'catatan',
        'tanggal_pengajuan',
        'tanggal_selesai',
    ];

    protected $casts = [
        'jumlah_bruto'   => 'decimal:2',
        'biaya_admin'    => 'decimal:2',
        'jumlah_bersih'  => 'decimal:2',
        'tanggal_pengajuan' => 'datetime',
        'tanggal_selesai'   => 'datetime',
    ];

    const BIAYA_ADMIN_PERSEN = 2; // 2%

    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class);
    }

    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending'   => 'Menunggu Konfirmasi',
            'diproses'  => 'Sedang Diproses',
            'selesai'   => 'Dana Telah Ditransfer',
            'ditolak'   => 'Ditolak',
            default     => 'Tidak Diketahui',
        };
    }

    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'pending'   => 'bg-label-warning',
            'diproses'  => 'bg-label-info',
            'selesai'   => 'bg-label-success',
            'ditolak'   => 'bg-label-danger',
            default     => 'bg-label-secondary',
        };
    }

    public function getFormattedJumlahBrutoAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bruto, 0, ',', '.');
    }

    public function getFormattedBiayaAdminAttribute()
    {
        return 'Rp ' . number_format($this->biaya_admin, 0, ',', '.');
    }

    public function getFormattedJumlahBersihAttribute()
    {
        return 'Rp ' . number_format($this->jumlah_bersih, 0, ',', '.');
    }

    public static function hitungBiayaAdmin(float $bruto): array
    {
        $biaya = $bruto * (self::BIAYA_ADMIN_PERSEN / 100);
        return [
            'bruto'  => $bruto,
            'biaya'  => $biaya,
            'bersih' => $bruto - $biaya,
        ];
    }
}
