<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orden extends Model
{
    use HasFactory;

    protected $fillable = [
        'servicio_id' ,
        'cliente_id' ,
        'vehiculo_id' ,
        'motivo' ,
        'horario' ,
        'estado' ,
    ];

    protected $table = 'ordens';

    public function clientes()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
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


    public function items(){
        return $this->belongsToMany(Item::class,'items_x_ordens');
    }

    public function facturas(){

        return $this->hasMany(Factura::class);
    }

}







