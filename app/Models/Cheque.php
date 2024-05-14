<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cheque extends Model
{
    use HasFactory;

    protected $fillable = [
'banco_id',
'cliente_id',
'pago_id',
'pago_id',
'vencimiento',
'monto',
'nro_cheque',
'estado',
    ];
}
