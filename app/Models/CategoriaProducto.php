<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CategoriaProducto extends Model
{
    use SoftDeletes;
    use HasFactory;

    public function productos(){

        return $this->belongsTo(Producto::class,'categoria_producto_id');
    }

}
