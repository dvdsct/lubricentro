<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PedidoProveedor extends Model
{
    use HasFactory;
    use SoftDeletes;

    
    protected $fillable = [
        'proveedor_id',
        'tipo_pedido_id',
        'fecha_ingreso',
        'observaciones',
        'estado',
        'sucursal_id',
    ];


    public function proveedores()
    {

        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items(){
        return $this->belongsToMany(PedItem::class, 'item_x_pedidos');
    }

    public function tipos(){

        return $this->belongsTo(TipoPedido::class,'tipo_pedido_id');
    }
}
