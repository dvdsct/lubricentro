<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarjeta extends Model
{
    use HasFactory;

    protected $fillable = ['estado', 'descuento','interes'];

    public function planes(){
        return $this->hasMany(Plan::class,'plan_id');
    }
}
