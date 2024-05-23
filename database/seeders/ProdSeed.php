<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdSeed extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos2 =[
            ['Lavado completo','','6500','','','',''],
            ['Lavado Premium','','12000','','','',''],
            ['Lavado de Motor','','12000','','','',''],
            ['Service MO','','','','','',''],
            ['Frenos MO','','15000','','','',''],
        ];
    
    
    
    
            foreach ($productos2 as $prod2) {
    
                $p = Producto::firstOrCreate([
                    'proveedor_id' => '1',
                    'precio_venta'  => $prod2[2],
                    'descripcion' => $prod2[1],
                    'codigo' => $prod2[0],
                    'estado' => '1',
                ]);
    
                Stock::create([
    
                    'producto_id' => $p->id,
                    'sucursal_id' => '1',
                    'cantidad' => '1000000',
                    'unidad' => 'un',
                    'estado' => '1',
                    'ideal' => '8',
                    'escaso' => '3',
                ]);
            }
    }
}