<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedioPago extends Model
{
    use HasFactory;

    protected $fillable = [
        'descripcion',
        'estado',
    ];

    
    public function pagos()
    {

        return $this->hasMany(Pago::class);
    }
}
