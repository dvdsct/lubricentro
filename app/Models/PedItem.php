<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedItem extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = [
    'producto_id',
    'precio',
    'estado',
    'cantidad',
    'subtotal',
    'estado',
];

    public function pedidos(){

        return $this->belongsToMany(PedidoProveedor::class, 'item_x_pedidos');
    }

    public function productos()
    {

        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
