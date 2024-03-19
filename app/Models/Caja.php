<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caja extends Model
{
    use HasFactory;
    protected $fillable = ['estado', 'user_id'];

    public function pagos(){

        return $this->belongsToMany(Pago::class,'pagos_x_cajas');
    }
}
