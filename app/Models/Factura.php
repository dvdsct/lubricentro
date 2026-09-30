<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'orden_id',
        'pedido_proveedor_id',
        'proveedor_id',
        'tipo_factura_id',
        'numero_factura',
        'fecha_emision',
        'fecha_vencimiento',
        'subtotal',
        'intereses',
        'descuentos',
        'total',
        'monto_bloqueado',
        'saldo_pendiente',
        'escenario_recepcion',
        'observaciones',
        'estado',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_vencimiento' => 'date',
        'total' => 'decimal:2',
        'monto_bloqueado' => 'decimal:2',
        'saldo_pendiente' => 'decimal:2',
    ];

    public function ordenes()
    {
        return $this->belongsTo(Orden::class, 'orden_id');
    }

    public function pedidoProveedor()
    {
        return $this->belongsTo(PedidoProveedor::class, 'pedido_proveedor_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function tipoFactura()
    {
        return $this->belongsTo(TipoFactura::class, 'tipo_factura_id');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'factura_id');
    }

    public function notasCredito()
    {
        return $this->hasMany(NotaCredito::class, 'factura_id');
    }

    public function ordenPagoItems()
    {
        return $this->hasMany(OrdenPagoFactura::class, 'factura_id');
    }

    public function getMontoDisponiblePagoAttribute(): float
    {
        $saldo = floatval($this->saldo_pendiente > 0 ? $this->saldo_pendiente : $this->total);
        $bloqueado = floatval($this->monto_bloqueado ?? 0);
        return max(0, $saldo - $bloqueado);
    }
}
