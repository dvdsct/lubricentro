<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;
    protected $fillable = [
        'descripcion',
        'stock',
        'costo',
        'codigo',
        'subcategoria_producto_id',
        'precio_venta',
        'categoria_producto_id',
        'precio_presupuesto',
        'monto',
        'porcentaje',
        'codigo_de_barras'

    ];



    public function stocks(){

        return $this->hasMany(Stock::class);
    }
    public function items(){

        return $this->hasMany(Item::class);
    }
    public function presItems(){

        return $this->hasMany(PresupuestoItem::class);
    }

    public function peditems(){

        return $this->hasMany(PedItem::class);
    }

    public function proveedores(){
        return $this->belongsToMany(Proveedor::class,'producto_x_proveedors');
    }

    public function categorias()
    {

        return $this->hasMany(CategoriaProducto::class);
    }


}
