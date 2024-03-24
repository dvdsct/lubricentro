<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehiculo extends Model
{
    use HasFactory;

    protected $fillable = [

        'tipo_vehiculo_id',
        'modelo_vehiculo_id',
        'marca_vehiculo_id',
        'dominio',
        'color',
        'version',
        'año',
        'estado'

    ];


    public function clientes(){

        return $this->belongsToMany(Cliente::class,'vehiculos_x_clientes', 'vehiculo_id', 'cliente_id');
    }


    public function tipoVehiculos()
    {
        return $this->belongsTo(TipoVehiculo::class, 'tipo_vehiculo_id');
    }

    public function marcas()
    {
        return $this->belongsTo(MarcaVehiculo::class, 'marca_vehiculo_id');
    }

    public function modelos()
    {
        return $this->belongsTo(ModeloVehiculo::class, 'modelo_vehiculo_id');
    }
    public function ordenes()
    {
        return $this->belongsTo(Orden::class);
    }
}

