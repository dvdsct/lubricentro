<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CuentaCorrienteProveedor extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cuenta_corriente_proveedors';

    protected $fillable = [
        'proveedor_id',
        'fecha',
        'tipo_movimiento',
        'comprobante_tipo',
        'comprobante_numero',
        'descripcion',
        'debe',
        'haber',
        'saldo',
        'monto_bloqueado',
        'factura_id',
        'nota_credito_id',
        'orden_pago_id',
        'pago_id',
        'pedido_proveedor_id',
        'estado',
    ];

    protected $casts = [
        'fecha' => 'date',
        'debe' => 'decimal:2',
        'haber' => 'decimal:2',
        'saldo' => 'decimal:2',
        'monto_bloqueado' => 'decimal:2',
    ];

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function notaCredito()
    {
        return $this->belongsTo(NotaCredito::class, 'nota_credito_id');
    }

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'orden_pago_id');
    }

    public function pago()
    {
        return $this->belongsTo(Pago::class, 'pago_id');
    }

    public function pedidoProveedor()
    {
        return $this->belongsTo(PedidoProveedor::class, 'pedido_proveedor_id');
    }
}
