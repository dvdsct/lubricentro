<?php

namespace Tests\Feature;

use App\Models\CategoriaProducto;
use App\Models\Producto;
use App\Models\Stock;
use App\Models\SubcategoriaProducto;
use App\Models\Sucursal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PdfStockTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Sucursal::create(['nombre_sucursal' => 'Central', 'estado' => 1]);
    }

    public function test_generar_pdf_de_stock_exitosamente(): void
    {
        $cat = CategoriaProducto::create(['descripcion' => 'Filtros', 'estado' => 1]);
        $sub = SubcategoriaProducto::create(['descripcion' => 'Aceite', 'categoria_producto_id' => $cat->id, 'estado' => 1]);

        $prod = Producto::create([
            'codigo' => 'WO-205',
            'descripcion' => 'Filtro Wega',
            'costo' => 5000,
            'precio_venta' => 8500,
            'categoria_producto_id' => $cat->id,
            'subcategoria_producto_id' => $sub->id,
            'es_provisional' => false,
        ]);

        Stock::create([
            'producto_id' => $prod->id,
            'cantidad' => 10,
            'sucursal_id' => 1,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('pdf.stock'));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertTrue(strlen($response->getContent()) > 1000);
    }

    public function test_generar_pdf_de_stock_con_filtros(): void
    {
        $cat = CategoriaProducto::create(['descripcion' => 'Aceites', 'estado' => 1]);
        $sub = SubcategoriaProducto::create(['descripcion' => 'Sintéticos', 'categoria_producto_id' => $cat->id, 'estado' => 1]);

        $prod = Producto::create([
            'codigo' => 'ELAION-F50',
            'descripcion' => 'Elaion F50 5W-40',
            'costo' => 12000,
            'precio_venta' => 19000,
            'categoria_producto_id' => $cat->id,
            'subcategoria_producto_id' => $sub->id,
            'es_provisional' => false,
        ]);

        Stock::create([
            'producto_id' => $prod->id,
            'cantidad' => 25,
            'sucursal_id' => 1,
        ]);

        $user = User::factory()->create();

        $response = $this->actingAs($user)->get(route('pdf.stock', [
            'subcategoria_id' => $sub->id,
            'query' => 'Elaion'
        ]));

        $response->assertStatus(200);
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
    }
}
