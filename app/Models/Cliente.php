<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cliente extends Model
{
    use SoftDeletes;
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
    public function pagosctas()
    {
        return $this->hasMany(PagoCtacte::class);
    }
    public function presupuestos()
    {
        return $this->hasMany(Presupuesto::class);
    }
}
