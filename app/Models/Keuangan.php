<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Keuangan extends Model
{
    use HasFactory;

    protected $table = 'keuangan';
    protected $guarded = [
        'id'
    ];

    public function rekening()
    {
        return $this->belongsTo(Rekening::class, 'id_rekening');
    }

    public function pajak()
    {
        return $this->belongsTo(Pajak::class, 'id_sumber')->where('sumber_transaksi', 'pajak');
    }

    public function bahanbakar()
    {
        return $this->belongsTo(Bahanbakar::class, 'id_sumber')->where('sumber_transaksi', 'pengeluaran_bbm');
    }

    public function pemeliharaan()
    {
        return $this->belongsTo(Pemeliharaan::class, 'id_sumber')->where('sumber_transaksi', 'pemeliharaan');
    }
}