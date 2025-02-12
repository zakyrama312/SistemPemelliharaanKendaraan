<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rekening extends Model
{
    use HasFactory;

    protected $table = 'rekening';
    protected $guarded = ['id'];

    public function rekening()
    {
        return $this->hasMany(Rekening::class, 'id_rekening');
    }
}