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

        static::creating(function ($pemeliharaan) {
            if (!$pemeliharaan->tanggal_pemeliharaan_berikutnya) {
                $kendaraan = Kendaraan::find($pemeliharaan->id_kendaraan);
                $intervalBulan = $kendaraan->interval_bulan ?? 3; // Default 3 bulan
                $pemeliharaan->tanggal_pemeliharaan_berikutnya = date('Y-m-d', strtotime($pemeliharaan->tanggal_pemeliharaan_sebelumnya . " +{$intervalBulan} months"));
            }
        });

        static::updating(function ($pemeliharaan) {
            if ($pemeliharaan->isDirty('tanggal_pemeliharaan_sebelumnya')) {
                $kendaraan = Kendaraan::find($pemeliharaan->id_kendaraan);
                $intervalBulan = $kendaraan->interval_bulan ?? 3;
                $pemeliharaan->tanggal_pemeliharaan_berikutnya = date('Y-m-d', strtotime($pemeliharaan->tanggal_pemeliharaan_sebelumnya . " +{$intervalBulan} months"));
            }
        });
    }
}