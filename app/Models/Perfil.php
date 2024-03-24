<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $fillable = [

        'persona_id'

    ];

    public function personas(){

        return $this->hasOne(Persona::class,'id');
    }

    public function users(){

        return $this->hasOne(User::class);
    }

    public function proveedores(){

        return $this->hasMany(Proveedor::class);
    }
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }

    public function clientesV()
    {
        return $this->belongsTo(VehiculosXCliente::class,'cliente_id');
    }
}
