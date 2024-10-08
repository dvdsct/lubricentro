<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\PagoCtacteController;
use App\Http\Controllers\PagoTarjetaController;
use App\Http\Controllers\PagoTransferenciaController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TurnosController;
use App\Http\Controllers\PedidoProveedorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\ProveedoresController;
use App\Http\Controllers\TarjetaController;
use App\Models\PagoCtacte;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('venta');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('dash', DashboardController::class);
    Route::resource('productos',ProductoController::class);
    Route::resource('ordenes',OrdenController::class);
    Route::resource('stock',StockController::class);
    Route::resource('venta',VentaController::class);
    Route::resource('turnos',TurnosController::class);
    Route::resource('pedidos',PedidoProveedorController::class);
    Route::resource('presupuesto',PresupuestoController::class);
    Route::resource('tarjetas',TarjetaController::class);
    Route::resource('pagos-tarjetas',PagoTarjetaController::class);
    Route::resource('pagos-transferencia',PagoTransferenciaController::class);
    Route::resource('pagos-cta',PagoCtacteController::class);
    Route::resource('proveedores',ProveedoresController::class);
    Route::get('/pdf/{orden}', 'App\Http\Controllers\PDFController@generatePDF')->name('pdf.orden');
    Route::get('/pdfpedido/{pedido}', 'App\Http\Controllers\PDFController@generatePedido')->name('pdf.pedido');
    Route::get('/pdfpres/{presupuesto}', 'App\Http\Controllers\PDFController@presupuesto')->name('pdf.presupuesto');
    Route::get('/pdfcaja/{caja}', 'App\Http\Controllers\PDFController@cierreCaja')->name('pdf.caja');
    Route::get('/pdf-stock', 'App\Http\Controllers\PDFController@pdfStock')->name('pdf.stock');

    Route::get('register', function () {
        return view('Lubricentro.Turnos.index');
         });

});
