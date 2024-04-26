<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'cantidad',    'precio',    'subtotal',    'estado',
    ];

    public function productos()
    {

        return $this->belongsTo(Producto::class, 'producto_id');
    }

    public function presupuestos(){

        return $this->belongsToMany(Presupuesto::class,'items_x_presupuestos');
    }
}
