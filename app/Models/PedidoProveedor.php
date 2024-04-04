<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PedidoProveedor extends Model
{
    use HasFactory;
    protected $fillable = [
        'proveedor_id',
        'tipo_pedido_id',
        'fecha_ingreso',
        'observaciones',
    ];


    public function proveedores()
    {

        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }
}
