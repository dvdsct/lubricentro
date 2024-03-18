<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MarcaVehiculo;
class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas=[
            'Fiat',
            'Volkswagen',
            'Ford',
            'Chevrolet',
            'Renault',
            'Citroën',
            'Toyota',
            'Peugeot',
            'Hyundai',
            'Jeep',
            'Nissan',
            'Mercedes-Benz',
            'BMW',
            'Suzuki',
            'Dodge',
            'Audi',
            'Honda',
            'KIA',
            'Subaru',
            'Volvo',
            'Land Rover',
            'Mitsubishi'
        ];

        foreach ($marcas as $m){

       MarcaVehiculo::create([

          'descripcion'=>$m
       ]);

        }
    }

}
