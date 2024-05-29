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
        $marcas = $autos = [
            ['1', 'FORD', 'Bronco',],
            ['1', 'ALFA ROMEO', '4C'],
            ['1', 'AUDI', 'A1'],
            ['1', 'CHERY', 'Arrizo 6'],
            ['1', 'CHEVROLET', 'Agile'],
            ['1', 'CHRYSLER', '300C'],
            ['1', 'CITROEN', 'AX'],
            ['1', 'DACIA', '1210 - 1300 - 1310 - 1410'],
            ['1', 'DAEWOO', 'Cielo GLE'],
            ['1', 'DODGE', 'Journey'],
            ['1', 'FERRARI', 'F 360 Modena'],
            ['1', 'FIAT', '125'],
            ['1', 'HONDA', 'Accord'],
            ['1', 'HYUNDAI', 'Accent'],
            ['1', 'IZUZU', 'Kenzu'],
            ['1', 'JAGUAR', 'Daimler Double Six'],
            ['1', 'JEEP', 'Cherokee'],
            ['1', 'KIA', 'Avella'],
            ['1', 'MAZDA', '121'],
            ['1', 'MERCEDES', '190'],
            ['1', 'MITSUBISHI', '3000 GT'],
            ['1', 'NISSAN', '100 NX'],
            ['1', 'PEUGEOT', '106'],
            ['1', 'PORCHE', '911'],
            ['1', 'REANULT', 'Arkana'],
            ['1', 'SEAT', 'Alhambra'],
            ['1', 'SUBARU', 'BRZ'],
            ['1', 'SUZUKI', 'Aerio'],
            ['1', 'TOYOTA', '4 Runner'],
            ['1', 'VOLKSWAGEN', '1500'],
            ['1', 'VOLVO', '240'],
            ['2','CHEVROLET','Avalanche'],
            
            ['2','CHRYSLER','Dakota'],
           
            ['2','DACIA','Logan Pick Up'],
            ['2','DODGE','Ram'],
            ['2','FIAT','Doblo'], 
            ['2','FORD','Courier'],
            ['2','HYUNDAI','Minibus County 29 Pasaj,'],
            ['2','ISUZU','NPK'],
            ['2','KIA','Asia topic'],
            ['2','MAZDA','Serie B'],
            ['2','MITSUBISHI','L 200'],
            ['2','NISSAN','Vanette'],
            ['2','PEUGEOT','504 Pick-Up'],
            ['2','RENAULT','Trafic'],
            ['2','TOYOTA','HI-ACE'],
            ['2','VOLKSWAGEN','Amarok'],

        ];
        foreach ($marcas as $m) {

            MarcaVehiculo::create([

                'tipo_vehiculo_id' => $m[0],
                'descripcion' => $m[1]
            ]);
        }
    }
}
