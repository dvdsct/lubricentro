<?php

namespace Database\Seeders;

use App\Models\Cajero;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Orden;
use App\Models\Persona;
use App\Models\Perfil;
use App\Models\Proveedor;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        Sucursal::create([
            'nombre_sucursal' => 'Av. Lugones',
            'estado' => '1',
        ]);


        Persona::create([
            'nombre' => 'Hugo',
            'apellido' => 'Larcher',
            'DNI' => '33520739',
            'fecha_nac' => '27/10/05',
            'estado' => '1',
        ]);

        Persona::create([
            'nombre' => 'Walter',
            'apellido' => 'Aguirre Pranzoni',
            'DNI' => '29861378',
            'fecha_nac' => '24/05/05',
            'estado' => '1',
        ]);
        Persona::create([
            'nombre' => 'Kari',
            'apellido' => '',
            'DNI' => '333333333',
            'fecha_nac' => '20/05/05',
            'estado' => '1',
        ]);

        Persona::create([
            'nombre' => 'Consumidor final',
            'apellido' => '',
            'DNI' => '0000000',
            'fecha_nac' => '1/12/05',
            'estado' => '1',
        ]);
        Persona::create([
            'nombre' => 'Proveedor',
            'apellido' => '',
            'DNI' => '0000000',
            'fecha_nac' => '1/12/05',
            'estado' => '1',
        ]);
        Persona::create([
            'nombre' => 'Vendedor',
            'apellido' => 'Vendedor',
            'DNI' => '33520739',
            'fecha_nac' => '27/10/05',
            'estado' => '1',
        ]);




        User::create([
            'name' => 'Kari',
            'email' => 'kari@test.com',
            'password' => bcrypt('Kari@159')
        ])->assignRole('admin');


        User::create([
            'name' => 'Walter',
            'email' => 'walter@test.com',
            'password' => bcrypt('Walter@159')
        ])->assignRole('admin');

        User::create([
            'name' => 'Hugo',
            'email' => 'hugo@test.com',
            'password' => bcrypt('Cajero@159')
        ])->assignRole('cajero');

        User::create([
            'name' => 'Vendedor',
            'email' => 'vendedor@test.com',
            'password' => bcrypt('Cajero@159')
        ])->assignRole('vendedor');


        Perfil::create([
            'persona_id' => '1',
            'user_id' => '3',
        ]);
        Perfil::create([
            'persona_id' => '2',
            'user_id' => '2',
        ]);
        Perfil::create([
            'persona_id' => '3',
            'user_id' => '1',
        ]);
        Perfil::create([
            'persona_id' => '4',
        ]);
        Perfil::create([
            'persona_id' => '5',
        ]);
        Perfil::create([
            'persona_id' => '6',
        ]);




        Empleado::create([
            'perfil_id' => '6',
            'puesto' => 'Vendedor',
            'estado' => '1',

        ]);

        Cliente::create([
            'perfil_id' => '4',
            'categoria' => '2',
            'lista_precios' => '3',


        ]);


        Cajero::create([
            'perfil_id' => '1',
            'sucursal_id' => '1'
        ]);


        Proveedor::create([

            'perfil_id' => '5',
            'tipo' => 'Mayorista',
            'estado' => '',
        ]);

        // Usuario encargado de Caja adicional
        $cajaUser = User::firstOrCreate(
            ['email' => 'caja@test.com'],
            [
                'name' => 'Encargado Caja',
                'password' => bcrypt('Caja@159'),
            ]
        );
        if (!$cajaUser->hasRole('cajero')) {
            $cajaUser->assignRole('cajero');
        }

        $cajaPersona = Persona::firstOrCreate(
            ['DNI' => '12345678'],
            [
                'nombre' => 'Encargado',
                'apellido' => 'Caja',
                'fecha_nac' => '1990-01-01',
                'estado' => '1',
            ]
        );

        $cajaPerfil = Perfil::firstOrCreate(
            ['persona_id' => $cajaPersona->id],
            ['user_id' => $cajaUser->id]
        );
        if (empty($cajaPerfil->user_id)) {
            $cajaPerfil->user_id = $cajaUser->id;
            $cajaPerfil->save();
        }

        $cajero = new Cajero();
        $cajero->perfil_id = $cajaPerfil->id;
        $cajero->sucursal_id = 1;
        // Evitar duplicado si ya existe
        if (!Cajero::where('perfil_id', $cajaPerfil->id)->exists()) {
            $cajero->save();
        }
    }
}
