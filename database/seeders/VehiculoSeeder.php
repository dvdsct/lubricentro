<?php

namespace Database\Seeders;

use App\Models\MarcaVehiculo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\ModeloVehiculo;
use App\Models\Orden;
use App\Models\TipoVehiculo;
use App\Models\Vehiculo;
use App\Models\VehiculosXCliente;

class VehiculoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {


        MarcaVehiculo::create(['descripcion' => 'Toyota']);
        MarcaVehiculo::create(['descripcion' => 'Ford']);

        ModeloVehiculo::create([
            'descripcion' => 'Corolla',

            'tipo_vehiculo_id' => 1, // ID del tipo de vehículo Automóvil
            'marca_vehiculo_id' => 1, // ID de la marca Toyota
            'estado' => 'Activo'
        ]);
        ModeloVehiculo::create([
            'descripcion' => 'Ranger',
            'tipo_vehiculo_id' => 2, // ID del tipo de vehículo Camioneta
            'marca_vehiculo_id' => 2, // ID de la marca Ford
            'estado' => 'Activo'
        ]);

        Vehiculo::create([
            'version' => 'v2',

            'modelo_vehiculo_id' => 1, // ID del modelo Corolla
            'dominio' => 'ABC123',
            'color' => 'Rojo',
            'version' => 'v2',
            'año' => '2107',

            'estado' => 'Activo'
        ]);

        Vehiculo::create([
            'modelo_vehiculo_id' => 2, // ID del modelo Ranger
            'dominio' => 'DEF456',
            'color' => 'Azul',
            'version' => 'v2',

            'año' => '2107',
            'estado' => 'Activo'
        ]);


        VehiculosXCliente::create([
            'cliente_id' => '1',
            'vehiculo_id' => '1'
        ]);


        VehiculosXCliente::create([
            'cliente_id' => '2',
            'vehiculo_id' => '2'
        ]);







        Orden::create([
            'empleado_id' => '1',
            'cliente_id' => '1',
            'servicio_id' => '1',
            'vehiculo_id' => '1',
            'motivo' => '1',
            'estado' => '1',


        ]);
        Orden::create([
            'empleado_id' => '1',
            'cliente_id' => '1',

            'servicio_id' => '2',
            'vehiculo_id' => '2',
            'motivo' => '2',
            'estado' => '1',


        ]);
        Orden::create([
            'empleado_id' => '1',
            'cliente_id' => '1',

            'servicio_id' => '2',
            'vehiculo_id' => '2',
            'motivo' => '2',
            'estado' => '1',


        ]);
    }
}
