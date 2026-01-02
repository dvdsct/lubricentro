<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $fillable = ['nombre_tarjeta','estado', 'descuento','interes'];

    public function planes(){
        // Relación correcta: un Tarjeta tiene muchos Planes por tarjeta_id
        return $this->hasMany(Plan::class,'tarjeta_id');
    }
}
