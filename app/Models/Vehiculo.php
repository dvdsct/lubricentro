<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    public function clientes(){

        return $this->belongsToMany(Cliente::class);
    }

    public function marcas(){

        return $this->hasOne(MarcaVehiculo::class);
    }


    public function modelos(){

        return $this->hasMany(ModeloVehiculo::class);
    }

    public function tipoVehiculos(){

        return $this->hasMany(TipoVehiculos::class);
    }
}

