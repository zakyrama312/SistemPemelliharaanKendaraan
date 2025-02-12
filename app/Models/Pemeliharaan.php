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
}