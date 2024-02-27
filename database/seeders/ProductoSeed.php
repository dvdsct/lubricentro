<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;

class ProductoSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos=[

           [ 'Aceite TOTAL7000 x 4lt', 38.000,'5','ooo736'],
           ['Aceite TOTAL9000 x 4lt', 61.999,'5','ooo736'],
           ['Aceite HELIX x 4lt 5w 30', 34.249,'5','ooo736'],
           ['Kit Wega FIAT Cronos argo', 41.797,'5','ooo736'],
           ['Kit Wega Suran FOX', 41.797,'5','ooo736'],
           ['Kit AMAROK', 63.193,'5','ooo736'],

        ];

        foreach($productos as $prod){

            Producto::create([
              'costo'=>$prod[1],
              'descripcion'=>$prod[0],
              'stock'=>$prod[2],
              'codigo'=>$prod[3],
            ]);
        }


    }
}
