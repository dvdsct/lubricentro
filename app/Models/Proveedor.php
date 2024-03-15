<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    use HasFactory;

    public function perfiles(){

        return $this->belongsTo(Perfil::class,'perfil_id');
    }

    public function productos(){

        return $this->hasMany(Producto::class);
    }


}
