<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'proveedor_id',
        'numero',
        'fecha_emision',
        'monto_total',
        'monto_pagado',
        'estado',
        'autorizado_por',
        'fecha_autorizacion',
        'autorizacion_tipo',
        'autorizacion_notas',
        'resumen_conciliado',
        'resumen_incidencias',
        'observaciones',
        'usuario_creador_id',
    ];

    protected $casts = [
        'fecha_emision' => 'date',
        'fecha_autorizacion' => 'datetime',
        'monto_total' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
        'resumen_conciliado' => 'boolean',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function items()
    {
        return $this->hasMany(OrdenPagoFactura::class, 'orden_pago_id');
    }

    public function facturas()
    {
        return $this->belongsToMany(Factura::class, 'orden_pago_facturas', 'orden_pago_id', 'factura_id')
                    ->withPivot(['monto_factura', 'monto_imputado', 'monto_pagado'])
                    ->withTimestamps();
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'orden_pago_id');
    }

    public function autorizadoPor()
    {
        return $this->belongsTo(User::class, 'autorizado_por');
    }

    public function usuarioCreador()
    {
        return $this->belongsTo(User::class, 'usuario_creador_id');
    }

    public function getSaldoPendienteAttribute(): float
    {
        return max(0, floatval($this->monto_total) - floatval($this->monto_pagado));
    }

    public function isAutorizada(): bool
    {
        return in_array($this->estado, ['autorizada', 'parcialmente_pagada', 'pagada']);
    }
}
