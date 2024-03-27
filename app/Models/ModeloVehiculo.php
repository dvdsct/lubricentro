<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeloVehiculo extends Model
{
    use HasFactory;

    public function vehiculos()
    {
        return $this->hasMany(Vehiculo::class,'modelo_vehiculo_id');
    }


    public function tipos(){
        return $this->belongsTo(TipoVehiculo::class,'tipo_vehiculo_id');
        }
    public function marcas(){
        return $this->belongsTo(MarcaVehiculo::class,'marca_vehiculo_id');
        }



}
