<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Kendaraan extends Model
{
    use HasFactory;

    protected $table = 'kendaraan';
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_users');
    }

    public function pemeliharaan()
    {
        return $this->hasMany(Pemeliharaan::class, 'id_kendaraan');
    }

    public function pajak()
    {
        return $this->hasMany(Pajak::class, 'id_kendaraan');
    }
}
