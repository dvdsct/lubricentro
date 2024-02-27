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


        $read = Permission::create(['name'=>'read']);
        $create = Permission::create(['name'=>'create']);
        $update = Permission::create(['name'=>'update']);
        $delete = Permission::create(['name'=>'delete']);

        $read->assignRole($admin);
        $read->assignRole($user);
        $create->assignRole($user);

        $create->assignRole($admin);
        $delete->assignRole($admin);
        $update->assignRole($admin);

        
    }
}
