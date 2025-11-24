<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SubcategoriaProducto;

class SubcategoriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategoria=[
            ['Monto',1],
            ['Porcentaje',1],
            ['Filtros de aire',1],
            ['Filtros de Combustible',1],
            ['Filtros de Habitaculo',1],
            ['Filtros de Aceite',1],
            ['Fluidos',1],
            ['Repuestos',1],
            ['Productos',1],
            ['Servicios',1],
            ['Mano de obra',1]
        ];


        foreach ($subcategoria as $sub){

           SubcategoriaProducto::create([

            'descripcion'=>$sub[0],
            'estado'=>$sub[1]

           ]);

        }
    }
}
