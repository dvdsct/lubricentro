<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    use HasFactory;

    protected $fillable = [

        'nombre',
        'apellido',
        'DNI',
        'numero_telefono',
        'fecha_nac',
        'estado'
    ];


    public function perfiles(){

        return $this->hasMany(Perfil::class);
    }













    public function domicilios(){

        return $this->hasMany(Domicilio::class);
    }

    public function telefonos(){

        return $this->hasMany(Telefono::class);
    }

    public function correos(){

        return $this->belongsToMany(Correo::class, 'correo_x_personas','correo_id');
    }
}
