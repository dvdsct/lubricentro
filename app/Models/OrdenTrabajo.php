<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdenTrabajo extends Model
{
    use HasFactory;

    protected $table = 'ordenes_trabajo';

    protected $guarded = [];

    protected $casts = [
        'fecha' => 'date',
        'aceite_envasado' => 'boolean',
        'aceite_suelto' => 'boolean',
        'aceite_lavado' => 'boolean',
        'filtro_aceite' => 'boolean',
        'filtro_combustible' => 'boolean',
        'filtro_aire' => 'boolean',
        'filtro_habitaculo' => 'boolean',
        'control_final' => 'boolean',
        'controles' => 'array',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculo::class, 'vehiculo_id');
    }

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
