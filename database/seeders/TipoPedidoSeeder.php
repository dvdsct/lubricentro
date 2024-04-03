<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TipoPedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tipo_pedidos')->insert([
            ['descripcion' => 'Aceites para motor', 'estado' => 1],
            ['descripcion' => 'Filtros de aceite', 'estado' => 1],
            ['descripcion' => 'Líquido de frenos', 'estado' => 1],
            ['descripcion' => 'Aditivos', 'estado' => 1],
            ['descripcion' => 'Grasas', 'estado' => 1],
            ['descripcion' => 'Anticongelantes y refrigerantes', 'estado' => 1],
            ['descripcion' => 'Productos de limpieza', 'estado' => 1],
            ['descripcion' => 'Accesorios y herramientas', 'estado' => 1],
            ['descripcion' => 'Kits de mantenimiento', 'estado' => 1],
            ['descripcion' => 'Productos para el cuidado del vehículo', 'estado' => 1],
            ['descripcion' => 'Repuestos', 'estado' => 1],
        ]);    }
}
