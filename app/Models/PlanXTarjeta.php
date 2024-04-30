<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanXTarjeta extends Model
{
    use HasFactory;


    public function tarjetas()
    {
        return $this->belongsToMany(Tarjeta::class, 'plan_x_tarjetas','tarjeta_id');
    }
    public function planes(){
        return $this->belongsToMany(Plan::class,'plan_x_tarjetas','plan_id');
    }
}
