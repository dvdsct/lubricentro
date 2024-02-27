<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    use HasFactory;

    public function perfiles(){

        return $this->hasOne(Perfil::class);
    }

    public function ordenes(){

        return $this->belongsToMany(Orden::class);
    }

    
}
