<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Correo extends Model
{
    use HasFactory;
    use SoftDeletes;

    public function personas()
    {
        return $this->belongsToMany(Persona::class, 'correo_x_personas');
    }
}
