<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable  = [
        'orden_id',
        'tipo_factura_id',
        'total',
        'estado',
        'subtotal',
        'intereses',
        'descuentos',


    ];


    public function ordenes(){
        return $this->belongsTo(Orden::class,'orden_id');
    }


    public function pagos()
    {

        return $this->hasMany(Pago::class);
    }

}
