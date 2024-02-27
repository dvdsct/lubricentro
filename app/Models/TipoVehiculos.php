<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoVehiculos extends Model
{
    use HasFactory;

    public function vehiculos(){

        return $this->belongsToMany(Vehiculo::class);
    }


    public function marcas(){

        return $this->belongsTo(MarcaVehiculo::class);
    }

    
}
