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
            ['De aire',2],
            ['De Combustible',2],
            ['De Habitaculo',2],
            ['De Aceite',2]
        ];


        foreach ($subcategoria as $sub){

           SubcategoriaProducto::create([

            'descripcion'=>$sub[0],
            'estado'=>$sub[1]

           ]);

        }
    }
}
