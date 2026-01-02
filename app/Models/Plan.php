<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = ['tarjeta_id','nombre_plan','descripcion_plan','estado','interes','descuento'];

    public function tarjetas(){
        return $this->belongsTo(Tarjeta::class,'tarjeta_id');
    }
}
