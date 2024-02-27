<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;


    public function empleados()
    {
        return $this->belongsTo(Empleado::class);
    }

    public function servicios()
    {
        return $this->belongsTo(Servicio::class,'servicio_id');
    }

    public function vehiculos()
    {
        return $this->belongsTo(Vehiculo::class,'vehiculos_x_clientes_id');
    }

    public function clientes()
    {
        return $this->belongsTo(Cliente::class,'vehiculos_x_clientes_id');
    }

    public function productos(){

        return $this->belongsToMany(Producto::class);
    }

}







