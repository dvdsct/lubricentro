<?php

namespace Database\Seeders;

use App\Models\Producto;
use App\Models\ProductoXProveedor;
use App\Models\Stock;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdSeed extends Seeder
{
    /**
     * Run the database seeds,
     */
    public function run(): void
    {
        $productos2 = [
            ['5W-40','MOTORCRAFT-1 LT','7','2','12','19853,61'],
            ['10W-40','MOTORCRAFT-1 LT','7','2','12','10285,61'],
            ['10W-40','MOTORCRAFT-4 LT','7','2','3','46482,01'],
            ['5W-40','ELF EVOLUTION 900 SRX-4 LT','7','2','24','84758,63'],
            ['10W-40','ELF EVOLUTION 700 STI-4 LT','7','2','24','55621,07'],
            ['10W-40','QUARTZ 7000-4 LT','7','2','20','49890,97'],
            ['5W-40','QUARTZ 9000-4 LT','7','2','24','80537,59'],
            ['15W-40','QUARTZ 5000-4 LT','7','2','6','35445,54'],
            ['5W-40','QUARTZ 9000-1 LT','7','2','19','22609,45'],
            ['10W-40','QUARTZ 7000-1 LT','7','2','16','16525,43'],
            ['15W-40','QUARTZ 5000-1 LT','7','2','10','14325,65'],
            ['5W-30','SHELL HELIX PROFESIONAL-4 LT','7','2','16','98160,01'],
            ['5W30','SHELL HELIX ULTRA AML-4 LT','7','2','8','98160,01'],
            ['0','SHELL HELIX ULTRA-0','7','2','0','0'],
            ['0W-20','SHELL HELIX ULTRA-4 LT','7','2','4','98160,01'],
            ['10W-40','SHELL HELIX HX7-4 LT','7','2','6','50000,01'],
            ['20W-50','SHELL HELIZ HX3-4 LT','7','2','7','41520,01'],
            ['20W-50','SHELL ADVANCE-1 LT','7','2','9','10880,01'],
            ['10W-40','SHELL HELIX HX7-1 LT','7','2','12','15760,01'],
            ['5W-40','SHELL HELIX HX8-1 LT','7','2','12','25680,01'],
            ['5W-30','SHELL HELIX PROFESIONAL-1 LT','7','2','11','25680,01'],
            ['NaN','GLACELF SUPRA-1 LT','7','2','19','11332,71'],
            ['NaN','GLACELF SUPRA RED-1 LT','7','2','12','11332,71'],
            ['NaN','AGUA DESTILADA -5 LT','7','2','3','2500,01'],
            ['5W40','SHELL HELIX ULTRA SUELTO-1 LT','7','2','180','11825,01'],
            ['10W40','SHELL HELIX HX7 SUELTO-1 LTS','7','2','159','7570,01'],
            ['15W40','SHEL HELIX HX5 SUELTO-1 LT','7','2','183','6500,01'],
            ['20W50','SHEL HELIX HX3 SUELTO-1 LT','7','2','137','6500,01'],
            ['10W40','TOTAL CLASSIC 7 SUELTO-1 LT','7','2','96','7570,01'],
            ['5W40','MOBIL SUPER -4 LT','7','2','1','98160,01'],
            ['5W40','MOBIL SUPER -1 LT','7','2','1','25680,01'],
            ['JFO OF 02','WEGA','6','2','1','12735,44'],
            ['JFO 981','WEGA','6','2','1','8436,81'],
            ['JFO 0211','WEGA','6','2','0','8436,81'],
            ['JFO 0210/1','WEGA','6','2','1','13217,41'],
            ['JFO 0209','WEGA','6','2','1','7830,56'],
            ['JFO 0197','WEGA','6','2','1','10520,57'],
            ['WOE 912','WEGA','6','2','3','11366,96'],
            ['WOE 710','WEGA','6','2','1','4066,84'],
            ['WOE 680','WEGA','6','2','4','10103,96'],
            ['WOE 640','WEGA','6','2','1','9851,35'],
            ['WOE 909','WEGA','6','2','1','12275,99'],
            ['WOE 626','WEGA','6','2','2','14044,51'],
            ['WOE 506','WEGA','6','2','1','12629,94'],
            ['WOE 505','WEGA','6','2','1','11240,01'],
            ['WOE 314','WEGA','6','2','3','9093,56'],
            ['WOE 313','WEGA','6','2','2','9851,35'],
            ['WOE 300/1','WEGA','6','2','1','8436,81'],
            ['WOE 130','WEGA','6','2','2','11240,65'],
            ['WOE 110','WEGA','6','2','2','9284,42'],
            ['WO 540','WEGA','6','2','1','30311,86'],
            ['WO 370','WEGA','6','2','2','6314,88'],
            ['WO 346','WEGA','6','2','0','11240,64'],
            ['WO 342','WEGA','6','2','2','22070,17'],
            ['WO 215','WEGA','6','2','2','9462,87'],
            ['WO 205','WEGA','6','2','3','6592,83'],
            ['WO 200','WEGA','6','2','0','5847,13'],
            ['WO 180','WEGA','6','2','2','8840,97'],
            ['WO 160','WEGA','6','2','1','7577,96'],
            ['WO 156','WEGA','6','2','2','8436,01'],
            ['WO 150','WEGA','6','2','2','7578,01'],
            ['WO 139','WEGA','6','2','1','8492,35'],
            ['WO 130','WEGA','6','2','0','4911,57'],
            ['WO 121','WEGA','6','2','2','5633,01'],
            ['WO 120','WEGA','6','2','0','3940,55'],
            ['WO 138','WEGA','6','2','1','8436,81'],
            ['FCD 0766','WEGA','4','2','2','8664,81'],
            ['FCD 0777','WEGA','4','2','6','12629,94'],
            ['FCD 0785','WEGA','4','2','0','16671,52'],
            ['FCD 0795','WEGA','4','2','2','21785,88'],
            ['FCD 0793','WEGA','4','2','2','37889,84'],
            ['FCD 0797','WEGA','4','2','2','22733,9'],
            ['FCD 0919','WEGA','4','2','4','49155'],
            ['FCD 0922','WEGA','4','2','2','18785,55'],
            ['FCD 2035','WEGA','4','2','1','37889,84'],
            ['FCD 2061','WEGA','4','2','2','33696,69'],
            ['FCD 2066/1','WEGA','4','2','1','34385,58'],
            ['FCD 2158','WEGA','4','2','1','33696,69'],
            ['FCD 2158/1','WEGA','4','2','3','37301,81'],
            ['FCI 1101 C','WEGA','4','2','2','10103,95'],
            ['FCI 1110 S','WEGA','4','2','1','18593,39'],
            ['FCI 1303','WEGA','4','2','1','42941,82'],
            ['FCI 1600','WEGA','4','2','3','6820,17'],
            ['FCI 1620','WEGA','4','2','2','7718,21'],
            ['FCI 1630','WEGA','4','2','2','6081,01'],
            ['FCI 1660','WEGA','4','2','0','6081,01'],
            ['FCI 1661','WEGA','4','2','2','8840,97'],
            ['FCI 1694','WEGA','4','2','1','8436,81'],
            ['FCI 1695','WEGA','4','2','1','7901,87'],
            ['JFC 207/3','WEGA','4','2','2','16848,35'],
            ['JFC 207/2','WEGA','4','2','1','10103,96'],
            ['JFC 383','WEGA','4','2','2','12629,94'],
            ['FCI 1610','WEGA','4','2','2','6314,01'],
            ['FAP 9299','WEGA','3','2','5','12629,95'],
            ['FAP 9283','WEGA','3','2','2','14703,32'],
            ['FAP 9273','WEGA','3','2','0','9598,76'],
            ['FAP 9027','WEGA','3','2','2','10577,93'],
            ['FAP 9121','WEGA','3','2','1','11240,65'],
            ['FAP 9054','WEGA','3','2','0','19546,85'],
            ['FAP 9003','WEGA','3','2','2','7952,08'],
            ['FAP 7019','WEGA','3','2','1','12629,95'],
            ['FAP 6012/2','WEGA','3','2','1','11240,65'],
            ['FAP 6003/1','WEGA','3','2','1','20207,92'],
            ['FAP 6000','WEGA','3','2','1','9598,76'],
            ['FAP 4892','WEGA','3','2','1','11644,81'],
            ['FAP 4895','WEGA','3','2','1','14650,74'],
            ['FAP 4046/1','WEGA','3','2','4','17681,92'],
            ['FAP 4873','WEGA','3','2','0','18402,22'],
            ['FAP 4872/1','WEGA','3','2','2','8202,35'],
            ['FAP 4865/1','WEGA','3','2','2','12051,74'],
            ['FAP 4054/3','WEGA','3','2','2','12276,54'],
            ['FAP 4046','WEGA','3','2','0','15155,94'],
            ['FAP 4043','WEGA','3','2','1','10078,96'],
            ['FAP 4041','WEGA','3','2','1','9346,16'],
            ['FAP 3602','WEGA','3','2','2','7250,42'],
            ['FAP 3288','WEGA','3','2','1','12377,35'],
            ['FAP  3286','WEGA','3','2','0','10356,56'],
            ['FAP  3285','WEGA','3','2','1','15155,94'],
            ['FAP 3271/4','WEGA','3','2','2','17681,93'],
            ['FAP 3270','WEGA','3','2','1','9598,76'],
            ['FAP 3261','WEGA','3','2','1','12629,95'],
            ['FAP 3233','WEGA','3','2','1','10103,96'],
            ['FAP 2827','WEGA','3','2','2','5472,92'],
            ['FAP 3289','WEGA','3','2','0','6881,07'],
            ['FAP 2802','WEGA','3','2','3','14852,83'],
            ['FAP 2219','WEGA','3','2','2','7830,57'],
            ['JFA 0998','WEGA','3','2','2','11801,98'],
            ['JFA 0992','WEGA','3','2','1','17681,93'],
            ['JFA 0602','WEGA','3','2','1','15745,72'],
            ['JFA 0282','WEGA','3','2','1','17681,92'],
            ['JFA 0137','WEGA','3','2','1','9851,35'],
            ['JFA OF00','WEGA','3','2','1','15408,54'],
            ['WR 295','WEGA','3','2','3','20634,42'],
            ['WR 200/3','WEGA','3','2','1','16848,35'],
            ['WR 198','WEGA','3','2','2','22733,91'],
            ['WR 192','WEGA','3','2','0','12629,94'],
            ['WR 150','WEGA','3','2','1','16848,35'],
            ['WR 110','WEGA','3','2','1','36300,01'],
            ['FAP 9286','WEGA','3','2','2','12200,01'],
            ['FAP 6013','WEGA','3','2','2','11240,01'],
            ['FAP 4033','WEGA','3','2','2','7810,01'],
            ['FAP 3269','WEGA','3','2','2','8860,01'],
            ['FAP 2829','WEGA','3','2','2','11240,01'],
            ['AKX 35725','WEGA','5','2','1','13878,81'],
            ['AKX 35723','WEGA','5','2','1','10103,96'],
            ['AKX 35347/CF','WEGA','5','2','0','18002,99'],
            ['AKX 35346 F','WEGA','5','2','2','33323,41'],
            ['AKX 35324','WEGA','5','2','1','10103,96'],
            ['AKX 35323','WEGA','5','2','0','9093,54'],
            ['AKX 35321','WEGA','5','2','0','6314,97'],
            ['AKX 35280','WEGA','5','2','1','10103,96'],
            ['AKX 35281','WEGA','5','2','1','10103,96'],
            ['AKX 35279','WEGA','5','2','2','7830,56'],
            ['AKX 35157','WEGA','5','2','2','8840,97'],
            ['AKX 3594 F ','WEGA','5','2','0','15155,94'],
            ['AKX 3534','WEGA','5','2','0','15155,94'],
            ['AKX 2161','WEGA','5','2','0','9093,56'],
            ['AKX 2108','WEGA','5','2','0','8436,81'],
            ['AKX 2102','WEGA','5','2','1','7577,96'],
            ['AKX 1993','WEGA','5','2','1','10103,95'],
            ['AKX 1967','WEGA','5','2','2','8436,81'],
            ['AKX 1965','WEGA','5','2','0','7830,57'],
            ['AKX 1944','WEGA','5','2','2','11240,65'],
            ['AKX 1959','WEGA','5','2','1','10381,81'],
            ['AKX 1925','WEGA','5','2','1','11247,67'],
            ['AKX 1700','WEGA','5','2','1','11240,65'],
            ['AKX 1452/2','WEGA','5','2','1','13525,99'],
            ['AKX 1444','WEGA','5','2','0','11240,65'],
            ['AKX 1399','WEGA','5','2','1','7072,77'],
            ['AKX 1397 ','WEGA','5','2','0','9851,35'],
            ['AKX 1201-2','WEGA','5','2','2','17681,92'],
            ['AKX 1140/C','WEGA','5','2','6','10356,56'],
            ['AKX 1140','WEGA','5','2','0','8840,97'],
            ['AKX 1100/C','WEGA','5','2','1','15913,73'],
            ['AKX 35711','WEGA','5','2','1','11033,01'],
            
                                
        ];



        foreach ($productos2 as $prod2) {

            $p = Producto::create([
                'precio_venta'  => $prod2[5],
                'categoria_producto_id'  => $prod2[3],
                'subcategoria_producto_id'  => $prod2[2],
                'descripcion' => $prod2[1],
                'codigo' => $prod2[0],
                'estado' => '1',
            ]);

            Stock::create([
                'producto_id' => $p->id,
                'sucursal_id' => '1',
                'cantidad' => $prod2[4],
                'unidad' => 'un',
                'estado' => '1',
                'ideal' => '8',
                'escaso' => '3',
            ]);
            ProductoXProveedor::create(
                [
                    'proveedor_id' => '1',
                    'producto_id' => $p->id,
                ]
            );
        }
    }
}
