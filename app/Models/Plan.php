<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    public function tarjetas(){
        return $this->belongsToMany(Tarjeta::class,'plan_x_tarjetas');
    }
}
