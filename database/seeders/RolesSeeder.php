<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {


        $admin = Role::create(['name'=>'admin']);
        $user = Role::create(['name'=>'user']);
        $cajero = Role::create(['name'=>'cajero']);
        $operador = Role::create(['name'=>'operador']);


        $read = Permission::create(['name'=>'read']);
        $hacerPedido = Permission::create(['name'=>'hacerPedido']);
        $recibirPedido = Permission::create(['name'=>'recibirPedido']);
        $create = Permission::create(['name'=>'create']);
        $update = Permission::create(['name'=>'update']);
        $delete = Permission::create(['name'=>'delete']);
        $caja = Permission::create(['name'=>'caja']);
        $stock = Permission::create(['name'=>'stock']);

        $caja->assignRole($cajero);
        $recibirPedido->assignRole($cajero);
        $caja->assignRole($admin);
        $stock->assignRole($admin);
        $read->assignRole($admin);
        $read->assignRole($user);
        $create->assignRole($user);

        $hacerPedido->assignRole($admin);
        $delete->assignRole($admin);
        $update->assignRole($admin);


    }
}
