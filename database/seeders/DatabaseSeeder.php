<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\TipoServicio;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(ServicioSeed::class);
        $this->call(RolesSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(VehiculoSeeder::class);
        $this->call(PagosSeeder::class);
        $this->call(ProductoSeed::class);
        $this->call(CategoriaSeed::class);
        $this->call(SubcategoriaSeed::class);
        $this->call(TipoFacturaSeed::class);
    }
}
