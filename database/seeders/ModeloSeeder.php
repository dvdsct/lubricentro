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
            ['Cronos',1,1],
            ['Punto',2,1],
            ['Argo',2,1],
            ['Toro',6,1],
            ['Uno',2,1],
            ['Strada',6,1],
            ['Mobi',2,1],
            ['500',2,1],
            ['500X',4,1],
            ['500L',2,1],
            ['Tipo',2,1],
            ['Panda',2,1],
            ['Spider',5,1],

            //Volkswagen
            ['Golf',2,2],
            ['Jetta',1,2],
            ['Passat',1,2],
            ['Tiguan',4,2],
            ['Atlas',4,2],
            ['Beetle',2,2],

            //Ford
            ['Mustang',3,3],
            ['F-150',6,3],
            ['Focus',2,3],
            ['Explorer',4,3],
            ['Escape',4,3],
            ['Edge',4,3],

            //Chevrolet
            ['Silverado',6,4],
            ['Camaro',3,4],
            ['Equinox',4,4],
            ['Tahoe',4,4],
            ['Malibu',1,4],
            ['Traverse',4,4],

            //Renault
            ['Clio',2,5],
            ['Megane',2,5],
            ['Capture',4,5],
            ['Kadjar',4,5],
            ['Duster',4,5],
            ['Twingo',2,5],
            ['Kangoo',4,5],
            ['Talisman',1,5],

            //Citroën
            ['C4',2,6],
            ['C5',1,6],
            ['C5 Aircross',4,6],
            ['C1',2,6],
            ['C3 Aircross',4,6],
            ['C4 Cactus',2,6],
            ['Berlingo',6,6],
            ['C-Zero',1,6],

            //Toyota
            ['Corolla', 1,7],
            ['Camry', 1,7],
            ['RAV4', 4,7],
            ['Prius', 2,7],
            ['Tacoma',6,7],
            ['Highlander',4,7],

            //Peugeot
            ['108',2,8],
            ['208',2,8],
            ['308',2,8],
            ['2008',4,8],
            ['3008',4,8],
            ['5008',4,8],
            ['508',1,8],
            ['208 GTi',2,8],
            ['308 GTi',2,8],
            ['Expert',7,8],
            ['Traveller',10,8],
            ['Partner',7,8],
            ['RCZ',3,8],
            ['108 TOP!',5,8],
            ['e-208',2,8],
            ['e-2008',4,8],
            ['508 SW',11,8],
            ['308 SW',11,8],
            ['3008 GT',11,8],

            //Hyundai
            ['Accent',1,9],
            ['Elantra',1,9],
            ['Sonata',1,9],
            ['Ioniq',2,9],
            ['Veloster',3,9],
            ['Kona',4,9],
            ['Tucson',4,9],
            ['Santa Fe',4,9],
            ['Palisade',4,9],
            ['Venue',4,9],
            ['Nexo',4,9],
            ['Genesis G70',1,9],
            ['Genesis G80',1,9],
            ['Genesis G90',1,9],

            //Jeep
            ['Wrangler',11,10],
            ['Grand Cherokee',4,10],
            ['Cherokee',4,10],
            ['Renegade',4,10],
            ['Compass',4,10],
            ['Gladiator',11,10],
            ['Wagoneer',4,10],
            ['Commander',4,10],
            ['Liberty',4,10],
            ['Patriot',4,10],


            //Nissan
            ['Altima',1,11],
            ['Maxima',1,11],
            ['Sentra',1,11],
            ['Versa',1,11],
            ['370Z',3,11],
            ['GT-R',3,11],
            ['Murano',4,11],
            ['Rogue',4,11],
            ['Pathfinder',4,11],
            ['Armada',4,11],
            ['Kicks',4,11],
            ['Juke',4,11],
            ['Qashqai',4,11],
            ['X-Trail',4,11],
            ['Frontier',4,11],
            ['Titan',6,11],
            ['NV200',7,11],
            ['Leaf',1,11],
            ['Note',2,11],
            ['Micra',2,11],

            //Mercedes-Benz
            ['A-Class',1,12],
            ['B-Class',11,12],
            ['C-Class',1,12],
            ['E-Class',1,12],
            ['S-Class',1,12],
            ['CLA-Class',3,12],
            ['CLS-Class',3,12],
            ['GLA-Class',4,12],
            ['GLB-Class',4,12],
            ['GLC-Class',4,12],
            ['GLE-Class',4,12],
            ['GLS-Class',4,12],
            ['G-Class',4,12],
            ['SL-Class',11,12],
            ['SLS AMG',3,12],
            ['SLC-Class',11,12],
            ['AMG GT',3,12],
            ['EQC',4,12],
            ['V-Class',11,12],
            ['X-Class',6,12],


            //BMW
            ['1 Series',2,13],
            ['2 Series',3,13],
            ['3 Series',1,13],
            ['4 Series',3,13],
            ['5 Series',1,13],
            ['6 Series',3,13],
            ['7 Series',1,13],
            ['8 Series',3,13],
            ['X1',4,13],
            ['X2',4,13],
            ['X3',4,13],
            ['X4',4,13],
            ['X5',4,13],
            ['X6',4,13],
            ['X7',4,13],
            ['Z4',11,13],
            ['i3',11,13],
            ['i4',11,13],
            ['iX3',11,13],
            ['iX',11,13],


            //Suzuki
            ['Swift',2,14],
            ['Baleno',2,14],
            ['Celerio',2,14],
            ['Ignis',4,14],
            ['Jimny',4,14],
            ['Vitara',4,14],
            ['SX4',2,14],
            ['S-Cross',4,14],
            ['Splash',2,14],
            ['Alto',2,14],
            ['Grand Vitara',4,14],
            ['Kizashi',1,14],
            ['Samurai',4,14],
            ['Wagon R',11,14],
            ['X-90',4,14],


            //Dodge
            ['Challenger',3,15],
            ['Charger',1,15],
            ['Durango',4,15],
            ['Journey',4,15],
            ['Grand Caravan',10,15],
            ['Dart',1,15],
            ['Viper',3,15],
            ['Ram',6,15],
            ['Nitro',4,15],
            ['Caliber',2,15],
            ['Avenger',1,15],
            ['Magnum',11,15],

            //Audi
            ['A1',2,16],
            ['A3',2,16],
            ['A4',1,16],
            ['A5',3,16],
            ['A5 Convertible',5,16],
            ['A6',1,16],
            ['A7',11,16],
            ['A8',1,16],
            ['Q2',4,16],
            ['Q3',4,16],
            ['Q5',4,16],
            ['Q7',4,16],
            ['Q8',4,16],
            ['TT',3,16],
            ['R8',3,16],
            ['S3',2,16],
            ['S4',1,16],
            ['S5',3,16],
            ['S6',1,16],
            ['S7',11,16],
            ['S8',1,16],

            //Honda
            ['Accord',1,17],
            ['Civic Sedán',1,17],
            ['Civic Hatchback',2,17],
            ['Civic Coupé',3,17],
            ['Fit',2,17],
            ['HR-V',4,17],
            ['CR-V',4,17],
            ['Pilot',4,17],
            ['Odyssey',10,17],
            ['Ridgeline',6,17],
            ['Insight',1,17],
            ['Clarity',1,17],
            ['Passport',4,17],
            ['Element',4,17],
            ['City',1,17],
            ['Jazz',2,17],
            ['Legend',1,17],

            //KIA
            ['Picanto',2,18],
            ['Rio',2,18],
            ['Forte',2,18],
            ['Cerato',2,18],
            ['Optima',1,18],
            ['Stinger',11,18],
            ['Soul',4,18],
            ['Niro',4,18],
            ['Sportage',4,18],
            ['Seltos',4,18],
            ['Sorento',4,18],
            ['Telluride',4,18],
            ['Carnival',10,18],
            ['Mohave',4,18],

            //Subaru
            ['Impreza Sedán',1,19],
            ['Impreza Hatchback',2,19],
            ['Legacy',1,19],
            ['WRX',1,19],
            ['BRZ',3,19],
            ['Crosstrek',4,19],
            ['Forester',4,19],
            ['Outback',4,19],
            ['Ascent',4,19],
            ['XV',4,19],
            ['Levorg',11,19],

            //Volvo
            ['S60',1,20],
            ['S90',1,20],
            ['V60',11,20],
            ['XC40',4,20],
            ['XC60',4,20],
            ['XC90',4,20],
            ['XC90 Recharge',4,20],
            ['XC60 Recharge',4,20],
            ['V60 Cross Country',11,20],
            ['V90 Cross Country',11,20],
            ['S60 Cross Country',1,20],

            //Land Rover
            ['Defender',4,21],
            ['Discovery',4,21],
            ['Discovery Sport',4,21],
            ['Range Rover',4,21],
            ['Range Rover Sport',4,21],
            ['Range Rover Velar',4,21],
            ['Range Rover Evoque',4,21],
            ['Range Rover Evoque Convertible',4,21],



          ];

        foreach ($modelos as $model) {

            ModeloVehiculo::create([

                'descripcion'=>$model[0],
                'tipo_vehiculo_id'=>$model[1],
                'marca_vehiculo_id'=>$model[2],
                'estado'=>'1'

            ]);
        }
    }
}
