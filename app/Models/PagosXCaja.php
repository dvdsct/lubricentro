<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagosXCaja extends Model
{
    use HasFactory;

    protected $fillable = ['pago_id',
    'caja_id',
    'estado',];
}
