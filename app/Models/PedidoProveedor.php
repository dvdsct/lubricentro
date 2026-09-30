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
        'fecha_ingreso_estimada',
        'fecha_recepcion',
        'observaciones',
        'condiciones_pago',
        'autorizado_por',
        'fecha_autorizacion',
        'autorizacion_tipo',
        'autorizacion_notas',
        'fecha_solicitud',
        'solicitado_medio',
        'motivo_rechazo',
        'escenario_recepcion',
        'total_estimado',
        'estado',
        'sucursal_id',
        'usuario_creador_id',
        'usuario_receptor_id',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_ingreso_estimada' => 'date',
        'fecha_recepcion' => 'datetime',
        'fecha_autorizacion' => 'datetime',
        'fecha_solicitud' => 'datetime',
        'total_estimado' => 'decimal:2',
    ];

    public function proveedores()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items()
    {
        return $this->belongsToMany(PedItem::class, 'item_x_pedidos');
    }

    public function itemsNuevo()
    {
        return $this->hasMany(PedidoProveedorItem::class, 'pedido_proveedor_id');
    }

    public function tipos()
    {
        return $this->belongsTo(TipoPedido::class, 'tipo_pedido_id');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function usuarioReceptor()
    {
        return $this->belongsTo(User::class, 'usuario_receptor_id');
    }

    public function autorizadoPor()
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function facturas()
    {
        return $this->hasMany(Factura::class, 'pedido_proveedor_id');
    }

    public function notasCredito()
    {
        return $this->hasMany(NotaCredito::class, 'pedido_proveedor_id');
    }

    public function getTotalAttribute(): float
    {
        $sum = $this->itemsNuevo()->sum('subtotal');
        if ($sum > 0) {
            return floatval($sum);
        }
        return floatval($this->items->sum('subtotal'));
    }
}
