<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    public function personas(){

        return $this->belongsTo(Persona::class,'persona_id');
    }

    public function users(){

        return $this->belongsTo(User::class);
    }

    public function proveedores(){

        return $this->hasOne(Proveedores::class);
    }
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
