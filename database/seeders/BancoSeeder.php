<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BancoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bancos = [
            ['descripcion' => 'Banco de la Nación Argentina', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Santander Río', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Galicia', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Macro', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco BBVA Argentina', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Itaú Argentina', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Patagonia', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Supervielle', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Hipotecario', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
            ['descripcion' => 'Banco Ciudad', 'sucursal_banco' => 'Sucursal Principal', 'estado' => 'Activo'],
        ];

        foreach ($bancos as $banco) {
            DB::table('bancos')->insert($banco);
        }    }
}
