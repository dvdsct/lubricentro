<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Domicilio extends Model
{
    use HasFactory;

    use SoftDeletes;

    public function personas(){

        return $this->belongsToMany(Persona::class);
    }
}
