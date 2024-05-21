<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cajero extends Model
{
    use HasFactory;

    public function perfiles()
    {
        return $this->belongsTo(Perfil::class,'perfil_id');
    }
    public function cajas(){
        return $this->hasMany(Caja::class);
    }
}
