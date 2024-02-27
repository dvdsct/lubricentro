<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    public function personas(){

        return $this->hasOne(Persona::class);
    }

    public function users(){

        return $this->hasOne(User::class);
    }

    public function proveedores(){

        return $this->hasOne(Proveedores::class);
    }
}
