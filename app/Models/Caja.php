<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = ['estado', 'cajero_id', 'monto_inicial', 'sucursal_id',
   'gastos',
    'venta',
    'transferencias',
    'tarjetas',
    'efectivo',
    'rendicion',
    'cheques',
    'cuenta_corriente',
    'observaciones',


];

    public function pagos()
    {

        return $this->belongsToMany(Pago::class, 'pagos_x_cajas');
    }

    public function sucursales()
    {

        return $this->belongsTo(Sucursal::class, 'sucursal_id');
    }
    public function cajeros()
    {

        return $this->belongsTo(Cajero::class, 'cajero_id');
    }
}
