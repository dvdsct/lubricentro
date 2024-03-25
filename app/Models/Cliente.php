<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;
    protected $fillable = [

        'perfil_id'

    ];


    public function perfiles()
    {
        return $this->belongsTo(Perfil::class,'perfil_id');
    }














    public function vehiculos()
    {
        return $this->belongsToMany(Vehiculo::class, 'vehiculos_x_clientes', 'cliente_id', 'vehiculo_id');
    }

    public function ordenes()
    {
        return $this->hasMany(Orden::class);
    }
}
