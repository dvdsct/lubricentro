<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TipoVehiculo;

class TipoVehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos=[
            'Sedán',
            'Hatchback',
            'Coupé',
            'SUV (Vehículo utilitario deportivo)',
            'Convertible',
            'Pick-up',
            'Furgoneta',
            'Motocicleta o Ciclomotor',
            'Vehículo de lujo',
            'VAN',
            'Otro'
        ];


        foreach ($tipos as $t){

            TipoVehiculo::create([

                'descripcion'=>$t
            ]);

        }
    }
}
