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
        'codigo'
    ];


    
    public function stocks(){

        return $this->hasMany(Stock::class);
    }
    public function items(){

        return $this->hasMany(Item::class);
    }

}
