<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Orden;
use App\Models\Servicio;
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
            $a = TipoServicio::create([
            'descripcion'=>$serv,
            'estado'=>'1'

            ]);

            Servicio::create([
                'tipo_servicio_id' => $a->id,
                'descripcion' => 'test'
            ]);

        }





    }
}
