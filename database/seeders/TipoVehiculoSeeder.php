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
        $tipos = [
            ['AUTO', 'FORD', 'Bronco',],
            ['CAMIONETA', 'VOLKSWAGEN', 'Transporter',]
        ];


        foreach ($tipos as $t) {

            TipoVehiculo::create([

                'descripcion' => $t[0]
            ]);
        }
    }
}
