<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    public function productos(){

        return $this->belongsToMany(Producto::class);
    }

    public function vehiculosxclientes(){

        return $this->belongsToMany(VehiculosxCliente::class);
    }

    public function empleados(){

        return $this->hasMany(Empleado::class);
    }

    public function servicios(){

        return $this->hasMany(Servicio::class);
    }

}

