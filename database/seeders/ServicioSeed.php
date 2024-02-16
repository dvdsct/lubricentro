<?php

namespace Database\Seeders;

use App\Models\TipoServicio;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicioSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $servicios= [
            'Lavado Basico',
            'Lavado Premium',
            'Mano de Obra Cambio Completo AUTO',
            'Mano de Obra Cambio Completo CAMIONETA/ UTILITARIO',
        ];

        foreach ($servicios as $serv){
            TipoServicio::create([
            'descripcion'=>$serv,
            'estado'=>'1'

            ]);

        }
    }
}
