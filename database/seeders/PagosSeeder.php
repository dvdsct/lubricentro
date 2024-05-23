<?php

namespace Database\Seeders;

use App\Models\MedioPago;
use App\Models\TipoPago;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $tipos =['Parcial','Total', 'Preventa','Diferido'];
        $medios = ['Tarjeta Credito','Efectivo','Cheque','Cuenta Corriente','Transferencia'];


        foreach($tipos as $t){
            TipoPago::create([

                'descripcion' => $t,
                'estado' => '1'

            ]);
        }

        foreach($medios as $t){
            MedioPago::create([

                'descripcion' => $t,
                'estado' => '1'
            ]);
        }
    }
}
