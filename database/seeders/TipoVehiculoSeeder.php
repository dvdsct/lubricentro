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
            'Automovil',
            'Camioneta',
            'Camion',
            'Autobus',
            'Motocicleta o Ciclomotor',
            'SUV (Vehículo utilitario deportivo)',
            'Vehículo de lujo',
            'Furgoneta',
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
