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
                'descripcion' => 'Visa',
                'descuento' => '5',
                'interes' => '20',
                'estado' => '1'
            ],
            [
                'descripcion' => 'Mastercard',
                'descuento' => '5',
                'interes' => '20',
                'estado' => '1'
            ],
            [
                'descripcion' => 'American Express',
                'descuento' => '5',
                'interes' => '20',
                'estado' => '1'
            ],
        ];

        DB::table('tarjetas')->insert($creditCards);
    }
}
