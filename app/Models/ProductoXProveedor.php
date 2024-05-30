<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductoXProveedor extends Model
{
    use HasFactory;

    protected $fillable =[
'proveedor_id',
'producto_id',
    ];

}
