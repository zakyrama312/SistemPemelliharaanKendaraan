<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pemeliharaan extends Model
{
    use HasFactory;

    protected $table = 'pemeliharaan';
    protected $guarded = ['id'];

    public function kendaraan()
    {
        return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
    }
    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening');
    }

    protected static function boot()
    {
        parent::boot();

        // static::creating(function ($pemeliharaan) {
        //     if (!$pemeliharaan->tanggal_pemeliharaan_berikutnya) {
        //         $kendaraan = Kendaraan::find($pemeliharaan->id_kendaraan);
        //         $intervalBulan = $pemeliharaan->interval_bulan ?? $kendaraan->interval_bulan ?? 3; // Ambil dari pemeliharaan dulu, jika null baru ambil dari kendaraan
        //         $pemeliharaan->tanggal_pemeliharaan_berikutnya = date('Y-m-d', strtotime($pemeliharaan->tanggal_pemeliharaan_sebelumnya . " +{$intervalBulan} months"));
        //     }
        // });

        // static::updating(function ($pemeliharaan) {
        //     if ($pemeliharaan->isDirty(['tanggal_pemeliharaan_sebelumnya', 'interval_bulan'])) {
        //         $intervalBulan = $pemeliharaan->interval_bulan ?? $pemeliharaan->kendaraan->interval_bulan ?? 3;
        //         $pemeliharaan->tanggal_pemeliharaan_berikutnya = date('Y-m-d', strtotime($pemeliharaan->tanggal_pemeliharaan_sebelumnya . " +{$intervalBulan} months"));
        //     }
        // });
    }

}
