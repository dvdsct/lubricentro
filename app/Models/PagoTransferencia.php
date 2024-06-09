<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoTransferencia extends Model
{
    use HasFactory;

    protected $fillable = [
        'cliente_id',
        'pago_id',
        'caja_id',
        'subtotal',
        'total',
        'nro_cupon',
        'estado',
    ];
}
