<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdenController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\TurnosController;
use App\Http\Controllers\PedidoProveedorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PresupuestoController;
use App\Http\Controllers\TarjetaController;

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
    return view('turnos');
});


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard');

    Route::resource('', DashboardController::class);
    Route::resource('productos',ProductoController::class);
    Route::resource('ordenes',OrdenController::class);
    Route::resource('stock',StockController::class);
    Route::resource('venta',VentaController::class);
    Route::resource('turnos',TurnosController::class);
    Route::resource('pedidos',PedidoProveedorController::class);
    Route::resource('presupuesto',PresupuestoController::class);
    Route::resource('tarjetas',TarjetaController::class);
    Route::get('/pdf/{orden}', 'App\Http\Controllers\PDFController@generatePDF')->name('pdf');
    Route::get('register', function () {
        return view('turnos');
         });

});