<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItemXPedido extends Model
{
    // use SoftDeletes;
    use HasFactory;
    protected $fillable = [
        'pedido_proveedor_id',
        'ped_item_id',
        'estado',
    ];
}
