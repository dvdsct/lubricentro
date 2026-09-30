<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenPagoFactura extends Model
{
    use HasFactory;

    protected $table = 'orden_pago_facturas';

    protected $fillable = [
        'orden_pago_id',
        'factura_id',
        'monto_factura',
        'monto_imputado',
        'monto_pagado',
    ];

    protected $casts = [
        'monto_factura' => 'decimal:2',
        'monto_imputado' => 'decimal:2',
        'monto_pagado' => 'decimal:2',
    ];

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'orden_pago_id');
    }

    public function factura()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }
}
