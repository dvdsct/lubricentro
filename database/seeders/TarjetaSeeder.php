<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TarjetaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $creditCards = [
            [
                'nombre_tarjeta' => 'Visa',
                'estado' => '1'
            ],
            [
                'nombre_tarjeta' => 'Mastercard',

                'estado' => '1'
            ],
            [
                'nombre_tarjeta' => 'American Express',

                'estado' => '1'
            ],
        ];
        $planes = [
            [
                'tarjeta_id' => '1',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '12 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '5',
                'interes' => '50',

            ],
            [
                'tarjeta_id' => '1',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '6 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '12',
                'interes' => '40',

            ],
            [
                'tarjeta_id' => '1',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '3 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '60',
                'interes' => '90',

            ],
            [
                'tarjeta_id' => '2',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '12 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '5',
                'interes' => '50',

            ],
            [
                'tarjeta_id' => '2',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '6 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '12',
                'interes' => '40',

            ],
            [
                'tarjeta_id' => '2',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '3 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '60',
                'interes' => '90',

            ],
            [
                'tarjeta_id' => '3',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '12 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '5',
                'interes' => '50',

            ],
            [
                'tarjeta_id' => '3',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '6 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '12',
                'interes' => '40',

            ],
            [
                'tarjeta_id' => '3',
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '3 Cuotas sin interes',
                'estado' => '1',
                'descuento' => '60',
                'interes' => '90',

            ],
        ];



        DB::table('tarjetas')->insert($creditCards);
        DB::table('plans')->insert($planes);
    }
}
