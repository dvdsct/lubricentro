<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [

        'factura_id',
        'cliente_id',
        'tipo_pago_id',
        'medio_pago_id',
        'efectivo',
        'total',
        'code_op',
        'estado',
    ];
}
