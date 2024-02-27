<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    public function ordenes(){

        return $this-> belongsToMany(Orden::class);
    }

    public function tipos(){

        return $this-> hasMany(TipoServicio::class);
    }

    
}
