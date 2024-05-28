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





        User::create([
            'name' => 'Kari',
            'email' => 'admin@test.com',
            'password' => bcrypt('Admin@159')
        ])->assignRole('admin');


        User::create([
            'name' => 'Walter',
            'email' => 'user@test.com',
            'password' => bcrypt('User@159')
        ])->assignRole('admin');

        User::create([
            'name' => 'Hugo',
            'email' => 'hugo@test.com',
            'password' => bcrypt('Cajero@159')
        ])->assignRole('cajero');


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




        // Empleado::create([
        //     'perfil_id' => '1',
        //     'puesto' => '1',
        //     'estado' => '1',

        // ]);

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
    }
}
