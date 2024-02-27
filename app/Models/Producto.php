<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    public function ordenes(){

        return $this->belongsToMany(Orden::class);
    }

    public function stocks(){

        return $this->belongsTo(Stock::class);
    }
}
