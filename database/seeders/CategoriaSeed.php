<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoriaProducto;

class CategoriaSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       $categorias = [
           ['Descuento', 1],
           ['Lavadero', 1],
           ['Lubricentro',1],
           ['Aceites',1],
           ['Filtros',1]
       ];

      foreach ($categorias as $cat){

        CategoriaProducto::create([

            'descripcion'=>$cat[0],
            'estado'=>$cat[1]
        ]);


      }
    }
}
