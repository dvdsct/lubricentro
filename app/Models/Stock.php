<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['cantidad','estado','sucursal_id','producto_id','stock_id'];

    public function productos(){

        return $this->belongsTo(Producto::class,'producto_id');
    }

    public function stocks(){
        return $this->belongsTo(Stock::class,'stock_id');
    }
}
