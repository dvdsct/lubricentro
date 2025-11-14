<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProveedorItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'pedido_proveedor_id',
        'producto_id',
        'cantidad_pedida',
        'cantidad_recibida',
        'costo_unitario',
        'subtotal',
        'estado_item',
    ];

    public function pedido()
    {
        return $this->belongsTo(PedidoProveedor::class, 'pedido_proveedor_id');
    }

    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
