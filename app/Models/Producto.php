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
        'precio_venta',
        'precio_presupuesto'
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




}
