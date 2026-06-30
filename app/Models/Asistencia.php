<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'user_id',
        'tipo',
        'fecha_hora',
        'latitud',
        'longitud',
    ];

    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    /**
     * Relación con el modelo User.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
