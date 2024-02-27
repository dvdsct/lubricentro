<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarcaVehiculo extends Model
{
    use HasFactory;




    public function modelos(){

        return $this->hasMany(ModeloVehiculo::class);
    }



}
