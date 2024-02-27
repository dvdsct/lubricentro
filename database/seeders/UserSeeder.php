<?php

namespace Database\Seeders;

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
        
        User::create([
            'name' => 'admin', 
            'email' => 'admin@test.com',
            'password' => bcrypt('Admin@159')
        ])->assignRole('admin');

        
        User::create([
            'name' => 'user', 
            'email' => 'user@test.com',
            'password' => bcrypt('User@159')
        ])->assignRole('user');

    }
}
