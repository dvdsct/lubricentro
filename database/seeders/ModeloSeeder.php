<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ModeloVehiculo;

class ModeloSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $modelos=[

            //Fiat
            ['Cronos',1],
            ['Punto',1],
            ['Argo',1],
            ['Toro',2],
            ['Uno',1],
            ['Strada',2],
            ['Mobi',1],
            ['500',1],
            ['500X',1],
            ['500L',1],
            ['Tipo',1],
            ['Panda',1],
            ['Spider',1],

            //Volkswagen
            ['Golf',1],
            ['Jetta',1],
            ['Passat',1],
            ['Tiguan',6],
            ['Atlas',6],
            ['Beetle',6],

            //Ford
            ['Mustang',1],
            ['F-150',2],
            ['Focus',1],
            ['Explorer',6],
            ['Escape',6],
            ['Edge',6],

            //Chevrolet
            ['Silverado',2],
            ['Camaro',1],
            ['Equinox',6],
            ['Tahoe',6],
            ['Malibu',1],
            ['Traverse',6],

            //Renault
            ['Clio',1],
            ['Megane',1],
            ['Capture',6],
            ['Kadjar',6],
            ['Duster',6],
            ['Twingo',1],
            ['Kangoo',2],
            ['Talisman',1],

            //Citroën
            ['C4',1],
            ['C5',1],
            ['C5 Aircross',6],
            ['C1',1],
            ['C3 Aircross',6],
            ['C4 Cactus',1],
            ['Berlingo',2],
            ['C-Zero',1],

            //Toyota
            ['Corolla', 1],
            ['Camry', 1],
            ['RAV4', 6],
            ['Prius', 1],
            ['Tacoma',2],
            ['Highlander',6],


            '108',1
            '208',1
            '308',1
            '2008',6
            '3008',6
            '5008',6
            '508',1
            '208 GTi',1
            '308 GTi',1
            'Expert',8
            'Traveller',9
            'Partner',1
            'RCZ',
            '108 TOP!',
            'e-208',
            'e-2008',
            '508 SW',
            '308 SW',
            '3008 GT',

            //Hyundai
            'Accent',
            'Elantra',
            'Sonata',
            'Ioniq',
            'Veloster',
            'Kona',
            'Tucson',
            'Santa Fe',
            'Palisade',
            'Venue',
            'Nexo',
            'Genesis G70',
            'Genesis G80',
            'Genesis G90',

            //Jeep
            'Wrangler',
            'Grand Cherokee',
            'Cherokee',
            'Renegade',
            'Compass',
            'Gladiator',
            'Wagoneer',
            'Commander',
            'Liberty',
            'Patriot',


            //Nissan
            'Altima',
            'Maxima',
            'Sentra',
            'Versa',
            '370Z',
            'GT-R',
            'Murano',
            'Rogue',
            'Pathfinder',
            'Armada',
            'Kicks',
            'Juke',
            'Qashqai',
            'X-Trail',
            'Frontier',
            'Titan',
            'NV200',
            'Leaf',
            'Note',
            'Micra',

            //Mercedes-Benz
            'A-Class',
            'B-Class',
            'C-Class',
            'E-Class',
            'S-Class',
            'CLA-Class',
            'CLS-Class',
            'GLA-Class',
            'GLB-Class',
            'GLC-Class',
            'GLE-Class',
            'GLS-Class',
            'G-Class',
            'SL-Class',
            'SLS AMG',
            'SLC-Class',
            'AMG GT',
            'EQC',
            'V-Class',
            'X-Class',

            //BMW
            '1 Series',
            '2 Series',
            '3 Series',
            '4 Series',
            '5 Series',
            '6 Series',
            '7 Series',
            '8 Series',
            'X1',
            'X2',
            'X3',
            'X4',
            'X5',
            'X6',
            'X7',
            'Z4',
            'i3',
            'i4',
            'iX3',
            'iX',


            //Suzuki
            'Swift',
            'Baleno',
            'Celerio',
            'Ignis',
            'Jimny',
            'Vitara',
            'SX4',
            'S-Cross',
            'Splash',
            'Alto',
            'Grand Vitara',
            'Kizashi',
            'Samurai',
            'Wagon R',
            'X-90',


            //Dodge
            'Challenger',
            'Charger',
            'Durango',
            'Journey',
            'Grand Caravan',
            'Dart',
            'Viper',
            'Ram',
            'Nitro',
            'Caliber',
            'Avenger',
            'Magnum',

            //Audi
            'A1',
            'A3',
            'A4',
            'A5',
            'A6',
            'A7',
            'A8',
            'Q2',
            'Q3',
            'Q5',
            'Q7',
            'Q8',
            'TT',
            'R8',
            'S3',
            'S4',
            'S5',
            'S6',
            'S7',
            'S8',

            //Honda
            'Accord',
            'Civic',
            'Fit',
            'HR-V',
            'CR-V',
            'Pilot',
            'Odyssey',
            'Ridgeline',
            'Insight',
            'Clarity',
            'Passport',
            'Element',
            'City',
            'Jazz',
            'Legend',

            //KIA
            'Picanto',
            'Rio',
            'Forte',
            'Cerato',
            'Optima',
            'Stinger',
            'Soul',
            'Niro',
            'Sportage',
            'Seltos',
            'Sorento',
            'Telluride',
            'Carnival',
            'Mohave',

            //Subaru
            'Impreza',
            'Legacy',
            'WRX',
            'BRZ',
            'Crosstrek',
            'Forester',
            'Outback',
            'Ascent',
            'XV',
            'Levorg',

            //Volvo
            'S60',
            'S90',
            'V60',
            'V90',
            'XC40',
            'XC60',
            'XC90',
            'XC90 Recharge',
            'XC60 Recharge',
            'V60 Cross Country',
            'V90 Cross Country',
            'S60 Cross Country',

            //Land Rover
            'Defender',
            'Discovery',
            'Discovery Sport',
            'Range Rover',
            'Range Rover Sport',
            'Range Rover Velar',
            'Range Rover Evoque',
            'Range Rover Evoque Convertible',



        ];

        foreach ($modelos as $model){

            ModeloVehiculo::create([




            ]);
        }
    }
}
