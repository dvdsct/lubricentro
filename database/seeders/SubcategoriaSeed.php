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
            ['De aire',1],
            ['De Combustible',1],
            ['De Habitaculo',1],
            ['De Aceite',1]
        ];


        foreach ($subcategoria as $sub){

           SubcategoriaProducto::create([

            'descripcion'=>$sub[0],
            'estado'=>$sub[1]

           ]);

        }
    }
}
