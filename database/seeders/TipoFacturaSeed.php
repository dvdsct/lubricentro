<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoFactura;

class TipoFacturaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos = [
            ['Remito',1],
            ['A',1],
            ['B',1],
            ['Consumidor final',1]
        ];

        foreach ($tipos as $t){

            TipoFactura::create([
                'descripcion'=>$t[0],
                'estado'=>$t[1]
            ]);
        }
    }
}
