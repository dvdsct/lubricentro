<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemsXPresupuesto extends Model
{
    use HasFactory;
    protected $fillable = [
        'presupuesto_item_id',	'presupuesto_id',	'estado',	
     ];
}
