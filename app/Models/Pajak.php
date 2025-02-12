<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pajak extends Model
{
  use HasFactory;

  protected $table = 'pajak';
  protected $guarded = ['id'];

  public function kendaraan()
  {
    return $this->belongsTo(Kendaraan::class, 'id_kendaraan');
  }
}