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

        $colores = [
        ['Blanco', '#ffffff'],
        ['Negro', '#000000'],
        ['Plateado', '#c0c0c0'],
        ['Gris', '#808080'],
        ['Azul', '#0000ff'],
        ['Rojo', '#ff0000'],
        ['Amarillo', '#ffff00'],
        ['Verde', '#008000'],
        ['Marrón', '#964b00'],
        ['Naranja', '#ff7f00'],
        ['Beige', '#f5f5dc'],
        ['Dorado', '#ffd700'],
        ['Blanco perla', '#eae0c8'],
        ['Rojo cereza', '#da1884'],
        ['Gris oscuro', '#a9a9a9'],
        ['Azul marino', '#000080'],
        ['Verde oliva', '#808000'],
        ['Rosa', '#ff007f'],
        ['Morado', '#800080'],
        ['Cyan', '#00ffff'],
        ['Magenta', '#ff00ff'],
        ['Turquesa', '#40e0d0'],
        ['Bronce', '#cd7f32'],
        ['Platino', '#e5e4e2'],
        ];


        foreach ($colores as $color){

            Colores::create([
                'descripcion'=>$color[0],
                'hexadecimal'=>$color[1],
                'estado'=>'1'
            ]);
        }
    }
}
