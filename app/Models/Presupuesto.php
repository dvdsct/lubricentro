<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presupuesto extends Model
{
    use HasFactory;

    protected $fillable = ['estado','cliente_id'];


    public function itemspres(){

        return $this->belongsToMany(PresupuestoItem::class,'items_x_presupuestos');
    }

    
    public function clientes()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }
    

    public function empleados()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }

    public function vehiculos()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }


    public function productos(){

        return $this->belongsToMany(Producto::class);
    }

}
