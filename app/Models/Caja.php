<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use SoftDeletes;

    use HasFactory;
    protected $fillable = ['estado', 'cajero_id'];

    public function pagos(){

        return $this->belongsToMany(Pago::class,'pagos_x_cajas');
    }



}
