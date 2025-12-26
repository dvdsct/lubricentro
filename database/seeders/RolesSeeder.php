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
        $vendedor = Role::create(['name'=>'vendedor']);


        $read = Permission::create(['name'=>'read']);
        $hacerPedido = Permission::create(['name'=>'hacerPedido']);
        $recibirPedido = Permission::create(['name'=>'recibirPedido']);
        $delPedido = Permission::create(['name'=>'delPedido']);
        $create = Permission::create(['name'=>'create']);
        $update = Permission::create(['name'=>'update']);
        $delete = Permission::create(['name'=>'delete']);
        $caja = Permission::create(['name'=>'caja']);
        $adminCajas = Permission::create(['name'=>'adminCajas']);
        $stock = Permission::create(['name'=>'stock']);
        $presupuestos = Permission::create(['name'=>'presupuestos']);

        $caja->assignRole($cajero);
        $caja->assignRole($vendedor);
        $adminCajas->assignRole($admin);
        $recibirPedido->assignRole($cajero);
        $hacerPedido->assignRole($cajero);
        $delPedido->assignRole($admin);
        // $caja->assignRole($admin);
        $stock->assignRole($admin);
        $presupuestos->assignRole($admin);
        $read->assignRole($admin);
        $read->assignRole($user);
        $create->assignRole($user);

        $hacerPedido->assignRole($admin);
        $delete->assignRole($admin);
        $update->assignRole($admin);


    }
}
