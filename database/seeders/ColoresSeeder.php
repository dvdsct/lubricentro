<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Colores;

class ColoresSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $colores = ['Blanco',
        'Negro',
        'Plateado',
        'Gris',
        'Azul',
        'Rojo',
        'Amarillo',
        'Verde',
        'Marrón',
        'Naranja',
        'Beige',
        'Dorado',
        'Blanco perla',
        'Rojo cereza',
        'Gris oscuro',
        'Azul marino',
        'Verde oliva',
        'Rosa',
        'Morado',
        'Cyan',
        'Magenta',
        'Turquesa',
        'Bronce',
        'Platino'
        ];


        foreach ($colores as $color){

            Colores::create([
                'descripcion'=>$color,
                'estado'=>'1'
            ]);
        }
    }
}
