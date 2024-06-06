<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoCtacte extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'plan_id',
        'cliente_id',
        'pago_id',
        'subtotal',
        'total',
        'nro_cupon',
        'estado',
        'caja_id',
    ];

    public function clientes()
    {
        return $this->belongsTo(Cliente::class,'cliente_id');
    }

}
