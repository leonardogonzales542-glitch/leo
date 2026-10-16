<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\CompraController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\VentaController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboards
use App\Http\Controllers\DashboardController;

Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'admin'])->name('admin.dashboard');
    Route::get('/vendedor/dashboard', [DashboardController::class, 'vendedor'])->name('vendedor.dashboard');
    Route::get('/cliente/dashboard', [DashboardController::class, 'cliente'])->name('cliente.dashboard');
});

use App\Http\Controllers\ConfiguracionController;
use App\Http\Controllers\CuponController;
use App\Http\Controllers\DevolucionController;
use App\Http\Controllers\EgresoController;
use App\Http\Controllers\VendedorController;

Route::prefix('admin')->middleware('auth')->group(function () {
    // Ventas — endpoints AJAX (deben ir antes del resource para no colisionar con {venta})
    Route::get('ventas/search/productos', [VentaController::class, 'searchProductos'])->name('ventas.search.productos');
    Route::get('ventas/search/clientes', [VentaController::class, 'searchClientes'])->name('ventas.search.clientes');
    Route::get('factura', [VentaController::class, 'factura'])->name('ventas.factura');

    Route::resource('productos', ProductoController::class);
    Route::resource('categorias', CategoriaController::class);
    Route::resource('clientes', ClienteController::class);
    Route::resource('proveedores', ProveedorController::class);
    Route::resource('usuarios', UsuarioController::class);
    Route::resource('ventas', VentaController::class);
    Route::resource('compras', CompraController::class);
    Route::resource('pedidos', PedidoController::class);
    Route::resource('devoluciones', DevolucionController::class);
    Route::resource('reportes', ReporteController::class);
    Route::resource('egresos', EgresoController::class);
    Route::resource('cupones', CuponController::class);
    Route::resource('vendedores', VendedorController::class);
    Route::resource('configuracion', ConfiguracionController::class);
});
