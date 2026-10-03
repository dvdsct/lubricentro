<?php

namespace Database\Seeders;

use App\Models\Empleado;
use App\Models\Perfil;
use App\Models\Persona;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class VendedorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la caché de permisos de Spatie para evitar errores de permisos no encontrados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. Crear o recuperar el rol 'vendedor'
        $roleVendedor = Role::firstOrCreate([
            'name' => 'vendedor',
            'guard_name' => 'web',
        ]);

        // 2. Definir y crear los permisos solicitados:
        //    - Agendar turnos
        //    - Hacer presupuestos
        //    - Acceder a la lista de clientes
        $permisos = [
            'turnos',
            'agendar-turnos',
            'presupuestos',
            'hacer-presupuestos',
            'clientes',
            'ver-clientes',
        ];

        $permisoModels = [];
        foreach ($permisos as $permisoNombre) {
            $permisoModels[] = Permission::firstOrCreate([
                'name' => $permisoNombre,
                'guard_name' => 'web',
            ]);
        }

        // Limpiar caché nuevamente para registrar los permisos creados
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Asignar exclusivamente estos permisos al rol vendedor
        $roleVendedor->syncPermissions($permisoModels);

        // 3. Crear o actualizar el usuario Vendedor
        $user = User::updateOrCreate(
            ['email' => 'vendedor@test.com'],
            [
                'name' => 'Vendedor',
                'password' => bcrypt('Vendedor@159'),
            ]
        );

        // Asignar el rol vendedor al usuario
        $user->syncRoles([$roleVendedor]);

        // 4. Crear o asociar Persona, Perfil y Empleado para mantener consistencia en la app
        $persona = Persona::firstOrCreate(
            ['DNI' => '33520739'],
            [
                'nombre' => 'Vendedor',
                'apellido' => 'Lubricentro',
                'fecha_nac' => '1990-01-01',
                'estado' => '1',
            ]
        );

        $perfil = Perfil::firstOrCreate(
            ['persona_id' => $persona->id],
            ['user_id' => $user->id]
        );

        if (empty($perfil->user_id)) {
            $perfil->user_id = $user->id;
            $perfil->save();
        }

        Empleado::firstOrCreate(
            ['perfil_id' => $perfil->id],
            [
                'puesto' => 'Vendedor',
                'estado' => '1',
            ]
        );
    }
}
