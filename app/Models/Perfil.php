<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    public function clientes()
    {
        return $this->hasMany(Cliente::class);
    }
}
