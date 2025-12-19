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
        'operacion',
        'referencia_type',
        'referencia_id',
        'user_id',
        'precio_unitario',
        'monto_total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
