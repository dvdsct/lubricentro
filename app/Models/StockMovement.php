<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'sucursal_id',
        'delta',
        'cantidad_anterior',
        'cantidad_nueva',
        'motivo',
        'referencia_type',
        'referencia_id',
        'user_id',
    ];
}
