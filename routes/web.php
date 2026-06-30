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
use App\Livewire\DescuentosCrud;
use App\Livewire\Clientes;
use App\Livewire\ClientProfile;
use App\Livewire\VehicleProfile;
use App\Http\Controllers\AsistenciaController;

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

// Página pública de clientes (listado de órdenes con búsqueda por cliente/patente)
Route::get('/clientes', Clientes::class)->name('clientes.index');
Route::get('/clientes/{cliente}/perfil', ClientProfile::class)->name('clientes.perfil');
Route::get('/vehiculos/{vehiculo}/perfil', VehicleProfile::class)->name('vehiculos.perfil');

// Ruta para la orden limpia (accesible sin autenticación)
Route::get('/pdf/orden-limpia', 'App\Http\Controllers\PDFController@generateOrdenLimpia')->name('pdf.orden.limpia');

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

    // Admin-only: Descuentos management (use 'can' middleware to avoid missing 'role' alias)
    Route::get('/descuentos', DescuentosCrud::class)
        ->name('descuentos.index')
        ->middleware('can:adminCajas');

    // Rutas protegidas de Asistencia para Empleados
    Route::get('/asistencia/registrar', [AsistenciaController::class, 'registrar'])->name('asistencia.registrar');
    Route::post('/asistencia/store', [AsistenciaController::class, 'store'])->name('asistencia.store');
    Route::post('/asistencia/logout', [AsistenciaController::class, 'logout'])->name('asistencia.logout');

    // Rutas protegidas de Asistencia para el Administrador
    Route::get('/asistencia/control', [AsistenciaController::class, 'control'])
        ->name('asistencia.control')
        ->middleware('can:adminCajas');
    Route::get('/asistencia/download-qr', [AsistenciaController::class, 'downloadQr'])
        ->name('asistencia.download-qr')
        ->middleware('can:adminCajas');
    Route::get('/asistencia/empleado/{user}', [AsistenciaController::class, 'empleadoPerfil'])
        ->name('asistencia.empleado-perfil')
        ->middleware('can:adminCajas');
});

// Rutas públicas de Asistencia (acceso mediante escaneo de QR)
Route::get('/asistencia/scan', [AsistenciaController::class, 'scan'])->name('asistencia.scan');
Route::post('/asistencia/verify-pin', [AsistenciaController::class, 'verifyPin'])->name('asistencia.verify-pin');

// Rutas públicas de Registro (protegidas por PIN)
Route::get('/register/pin', [AsistenciaController::class, 'registerPin'])->name('register.pin');
Route::post('/register/pin', [AsistenciaController::class, 'verifyRegisterPin'])->name('register.verify-pin');

Route::get('/register', function () {
    if (session('register_pin_verified') !== true) {
        return redirect()->route('register.pin');
    }
    return app(\Laravel\Fortify\Http\Controllers\RegisteredUserController::class)->create(request());
})->name('register');

Route::post('/register', function (Illuminate\Http\Request $request) {
    if (session('register_pin_verified') !== true) {
        abort(403, 'Acceso no autorizado.');
    }
    $response = app(\Laravel\Fortify\Http\Controllers\RegisteredUserController::class)->store($request);
    session()->forget('register_pin_verified');
    return $response;
});
