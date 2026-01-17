<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = ['cantidad','cantidad_num','estado','sucursal_id','producto_id','stock_id'];

    protected $casts = [
        'cantidad_num' => 'decimal:3',
    ];

    public function productos(){

        return $this->belongsTo(Producto::class,'producto_id');
    }

    public function stocks(){
        return $this->belongsTo(Stock::class,'stock_id');
    }
    /**
     * Devuelve la cantidad preferentemente como número.
     * Si existe `cantidad_num` lo devuelve, si no intenta parsear `cantidad`.
     */
    public function getCantidadAttribute($value)
    {
        if (!is_null($this->attributes['cantidad_num'] ?? null)) {
            return (float)$this->attributes['cantidad_num'];
        }

        if (is_null($value)) return null;

        $clean = preg_replace('/[^0-9,\.\-]/', '', (string)$value);
        $clean = str_replace(',', '.', $clean);
        return is_numeric($clean) ? (float)$clean : null;
    }

}
