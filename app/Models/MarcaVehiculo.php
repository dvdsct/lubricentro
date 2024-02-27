<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaVehiculo extends Model
{
    use HasFactory;

    public function vehiculo(){

        return $this->belongsToMany(Vehiculo::class);
    }

    public function modelos(){

        return $this->hasMany(Vehiculo::class);
    }

    public function tipos(){

        return $this->hasMany(TipoVehiculos::class);
    }
}
