<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sucursal extends Model
{
    use HasFactory;


    public function ordenes(){
        return $this->hasMany(Orden::class);
    }


    public function cajas(){
        return $this->hasMany(Caja::class);
    }


}
