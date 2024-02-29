<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    public function clientes(){

        return $this->belongsToMany(Cliente::class,'vehiculos_x_clientes', 'vehiculo_id', 'cliente_id');
    }

    public function marcas(){

        return $this->belongsTo(MarcaVehiculo::class,'id');
    }


    public function modelos(){

        return $this->belongsTo(ModeloVehiculo::class, 'id');
    }

    public function tipoVehiculos(){

        return $this->hasMany(TipoVehiculo::class);
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class, 'vehiculos_id');
    }
}

