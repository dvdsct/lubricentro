<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $fillable = [

        'factura_id',
        'cliente_id',
        'tipo_pago_id',
        'medio_pago_id',
        'efectivo',
        'total',
        'code_op',
        'estado',
    ];


    public function facturas()
    {

        return $this->belongsTo(Factura::class, 'factura_id');
    }


    public function medios()
    {

        return $this->belongsTo(MedioPago::class, 'medio_pago_id');
    }
    public function tipos()
    {

        return $this->belongsTo(TipoPago::class, 'tipo_pago_id');
    }
}
