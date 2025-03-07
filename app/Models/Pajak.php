<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pajak extends Model
{
  use HasFactory;

  protected $table = 'pajak';
  protected $guarded = ['id'];

  protected $casts = [
    'masa_berlaku' => 'date',
  ];


  public function kendaraan()
  {
    return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
  }
  public function rekening()
  {
    return $this->belongsTo(Rekening::class, 'id_rekening');
  }
}