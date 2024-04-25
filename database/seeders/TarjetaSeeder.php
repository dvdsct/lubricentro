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
                'descuento' => '5',
                'interes' => '20',
                'estado' => '1'
            ],
            [
                'nombre_tarjeta' => 'Mastercard',
                'descuento' => '5',
                'interes' => '15',
                'estado' => '1'
            ],
            [
                'nombre_tarjeta' => 'American Express',
                'descuento' => '5',
                'interes' => '35',
                'estado' => '1'
            ],
        ];
        $planes = [
            [
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '12 Cuotas sin interes',
                'estado' => '1'

            ],
            [
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '6 Cuotas sin interes',
                'estado' => '1'

            ],
            [
                'nombre_plan' => 'plan Tu Tarjeta',
                'descripcion_plan' => '3 Cuotas sin interes',
                'estado' => '1'

            ]
            ];
        $pxt = [
            [
                'tarjeta_id' => '1',
                'plan_id' => '1',
                'estado' => '1'
            ],
            [
                'tarjeta_id' => '2',
                'plan_id' => '3',
                'estado' => '1'
            ],
            [
                'tarjeta_id' => '3',
                'plan_id' => '2',
                'estado' => '1'
            ]
        ];


        DB::table('tarjetas')->insert($creditCards);
        DB::table('plans')->insert($planes);
        DB::table('plan_x_tarjetas')->insert($pxt);
    }
}
