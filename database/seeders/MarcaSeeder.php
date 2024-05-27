<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MarcaVehiculo;
class MarcaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $marcas=[
            'ALFA ROMEO',
            'AUDI',
            'BAIC',
            'BMW',
            'BUICK',
            'BYD',
            'CADILLAC',
            'CHANA',
            'CHANGAN',
            'CHERY',
            'CHEVROLET',
            'CHRYSLER',
            'CITROEN',
            'CN',
            'DACIA',
            'DAEWOO',
            'DAIHATSU',
            'DODGE',
            'DS',
            'EFFA',
            'FAW',
            'FERRARI',
            'FIAT',
            'FORD',
            'FOTON',
            'GEELY',
            'HAVAL',
            'HONDA',
            'HUMMER',
            'HYUNDAI',
            'ISUZU',
            'JAC',
            'JAGUAR',
            'JEEP',
            'KIA',
            'LADA',
            'LANDIRENZO',
            'LEXUS',
            'LIFAN',
            'MAHINDRA',
            'MASERATI',
            'MAZDA',
            'MERCEDES BENZ',
            'MINI',
            'MITSUBISHI',
            'NISSAN',
            'OPEL',
            'PEUGEOT',
            'PORSCHE',
            'PROTON',
            'RENAULT',
            'ROVER / LANDROVER',
            'SAAB',
            'SEAT',
            'SKODA',
            'SMART',
            'SSANG YONG',
            'SUBARU',
            'SUZUKI',
            'TATA MOTORS',
            'TOYOTA',
            'VOLKSWAGEN',
            'VOLVO',
            'ZOTYE',            
        ];

        foreach ($marcas as $m){

       MarcaVehiculo::create([

          'descripcion'=>$m
       ]);

        }
    }

}
