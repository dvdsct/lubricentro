<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ClienteRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roleCliente = Role::firstOrCreate(['name' => 'cliente']);
        $permissionPortal = Permission::firstOrCreate(['name' => 'portal-cliente']);

        if (!$roleCliente->hasPermissionTo($permissionPortal)) {
            $roleCliente->givePermissionTo($permissionPortal);
        }
    }
}
