<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'producto_id',
        'cantidad',    'precio',    'subtotal',    'estado',
    ];

    public function ordens()
    {

        return $this->belongsToMany(Orden::class, 'items_x_ordens');
    }
    public function productos()
    {

        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
