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

        return $this->belongsTo(Persona::class,'persona_id');
    }


    public function clientes()
    {
        return $this->hasMany(Cliente::class );
    }
    public function cajeros()
    {
        return $this->hasMany(Cajero::class );
    }






    public function users(){

        return $this->hasOne(User::class);
    }

    public function proveedores(){

        return $this->hasMany(Proveedor::class);
    }


    public function clientesV()
    {
        return $this->belongsTo(VehiculosXCliente::class,'cliente_id');
    }
}
