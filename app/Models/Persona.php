<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    public function perfiles(){

        return $this->hasOne(Perfil::class);
    }
    public function domicilios(){

        return $this->hasMany(Domicilio::class);
    }

    public function telefonos(){

        return $this->hasMany(Telefono::class);
    }

    public function correos(){

        return $this->hasMany(Correo::class);
    }
}
