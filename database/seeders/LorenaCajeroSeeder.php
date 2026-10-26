<?php

namespace Database\Seeders;

use App\Models\Cajero;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class LorenaCajeroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Asegurar roles
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $cajeroRole = Role::firstOrCreate(['name' => 'cajero']);

        // 2. Asegurar permisos requeridos
        $permHacerPedido = Permission::firstOrCreate(['name' => 'hacerPedido']);
        $permRecibirPedido = Permission::firstOrCreate(['name' => 'recibirPedido']);
        $permPedidos = Permission::firstOrCreate(['name' => 'pedidos']);
        $permCrearProveedores = Permission::firstOrCreate(['name' => 'crearProveedores']);
        $permProveedores = Permission::firstOrCreate(['name' => 'proveedores']);
        $permCrearProveedoresSlug = Permission::firstOrCreate(['name' => 'crear-proveedores']);

        // Asignar los permisos al admin por defecto
        $adminRole->givePermissionTo([
            $permHacerPedido,
            $permRecibirPedido,
            $permPedidos,
            $permCrearProveedores,
            $permProveedores,
            $permCrearProveedoresSlug,
        ]);

        // 3. Crear o actualizar el usuario Lorena
        $user = User::firstOrCreate(
            ['email' => 'lorena@test.com'],
            [
                'name' => 'Lorena',
                'password' => bcrypt('Lorena@159'),
            ]
        );

        // Asignar rol de cajero
        if (!$user->hasRole('cajero')) {
            $user->assignRole('cajero');
        }

        // 4. Asignar los permisos específicos solicitados directamente al usuario
        $user->givePermissionTo([
            'hacerPedido',
            'recibirPedido',
            'pedidos',
            'crearProveedores',
            'proveedores',
            'crear-proveedores',
        ]);

        // 5. Crear Persona, Perfil y Cajero para Lorena
        $persona = Persona::firstOrCreate(
            ['DNI' => '39876543'],
            [
                'nombre' => 'Lorena',
                'apellido' => 'González',
                'fecha_nac' => '1996-08-20',
                'estado' => '1',
            ]
        );

        $perfil = Perfil::firstOrCreate(
            ['user_id' => $user->id],
            ['persona_id' => $persona->id]
        );

        if (!$perfil->persona_id) {
            $perfil->persona_id = $persona->id;
            $perfil->save();
        }

        $sucursal = Sucursal::first() ?? Sucursal::create([
            'nombre_sucursal' => 'Av. Lugones',
            'estado' => '1',
        ]);

        Cajero::firstOrCreate(
            ['perfil_id' => $perfil->id],
            ['sucursal_id' => $sucursal->id]
        );

        // Limpiar caché de permisos de Spatie
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
