<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'factura_id',
        'orden_pago_id',
        'cliente_id',
        'proveedor_id',
        'tipo_pago_id',
        'medio_pago_id',
        'efectivo',
        'total',
        'code_op',
        'estado',
        'concepto',
        'in_out',
    ];

    public function facturas()
    {
        return $this->belongsTo(Factura::class, 'factura_id');
    }

    public function ordenPago()
    {
        return $this->belongsTo(OrdenPago::class, 'orden_pago_id');
    }

    public function proveedor()
    {
        return $this->belongsTo(Proveedor::class, 'proveedor_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function medios()
    {
        return $this->belongsTo(MedioPago::class, 'medio_pago_id');
    }

    public function tipos()
    {
        return $this->belongsTo(TipoPago::class, 'tipo_pago_id');
    }

    public function cajas()
    {
        return $this->belongsToMany(Caja::class, 'pagos_x_cajas');
    }

    public function pagosCta()
    {
        return $this->hasMany(PagoCtacte::class);
    }

    public function pagosTarjeta()
    {
        return $this->hasMany(PagoTarjeta::class);
    }

    public function pagosTransferencia()
    {
        return $this->hasMany(PagoTransferencia::class);
    }
}
