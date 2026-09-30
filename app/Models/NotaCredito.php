<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class NotaCredito extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factura_id',
        'proveedor_id',
        'pedido_proveedor_id',
        'numero',
        'fecha_emision',
        'monto',
        'monto_aplicado',
        'estado',
        'motivo',
        'observaciones',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'monto' => 'decimal:2',
        'monto_aplicado' => 'decimal:2',
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function pedidoProveedor()
    {
        return $this->belongsTo(PedidoProveedor::class, 'pedido_proveedor_id');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, floatval($this->monto) - floatval($this->monto_aplicado));
    }
}
