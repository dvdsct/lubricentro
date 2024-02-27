<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    public function perfiles(){

        return $this->hasOne(Perfil::class);
    }
    public function vehiculos(){

        return $this-> hasMany(Vehiculo::class);
    }

}
