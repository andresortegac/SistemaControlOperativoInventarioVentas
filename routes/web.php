<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\GastoController;
use App\Http\Controllers\ReporteController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\ConfiguracionController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Rutas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rutas protegidas
Route::middleware(['auth'])->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Perfil
    Route::get('/perfil', [AuthController::class, 'profile'])->name('perfil');
    Route::put('/perfil', [AuthController::class, 'updateProfile']);
    Route::get('/cambiar-password', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/cambiar-password', [AuthController::class, 'changePassword']);

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ========== PRODUCTOS ==========
    Route::prefix('productos')->name('productos.')->group(function () {
        Route::get('/', [ProductoController::class, 'index'])->name('index');
        Route::get('/create', [ProductoController::class, 'create'])->name('create');
        Route::post('/', [ProductoController::class, 'store'])->name('store');
        Route::get('/{producto}', [ProductoController::class, 'show'])->name('show');
        Route::get('/{producto}/edit', [ProductoController::class, 'edit'])->name('edit');
        Route::put('/{producto}', [ProductoController::class, 'update'])->name('update');
        Route::delete('/{producto}', [ProductoController::class, 'destroy'])->name('destroy');
        Route::post('/{producto}/activar', [ProductoController::class, 'activar'])->name('activar');
        
        // Código de barras
        Route::get('/{producto}/barcode', [ProductoController::class, 'generarCodigoBarras'])->name('barcode');
        Route::post('/imprimir-codigos', [ProductoController::class, 'imprimirCodigos'])->name('imprimir-codigos');
        
        // AJAX
        Route::get('/buscar/codigo', [ProductoController::class, 'buscarPorCodigo'])->name('buscar.codigo');
        Route::get('/buscar/ajax', [ProductoController::class, 'buscarAjax'])->name('buscar.ajax');
        
        // Inventario
        Route::get('/reporte/stock-bajo', [ProductoController::class, 'stockBajo'])->name('stock-bajo');
        Route::get('/reporte/inventario', [ProductoController::class, 'inventario'])->name('inventario');
    });

    // ========== CATEGORÍAS ==========
    Route::resource('categorias', CategoriaController::class);

    // ========== PROVEEDORES ==========
    Route::resource('proveedores', ProveedorController::class);
    Route::post('/proveedores/{proveedor}/activar', [ProveedorController::class, 'activar'])->name('proveedores.activar');

    // ========== CLIENTES ==========
    Route::resource('clientes', ClienteController::class);
    Route::post('/clientes/{cliente}/activar', [ClienteController::class, 'activar'])->name('clientes.activar');
    Route::get('/clientes/buscar/ajax', [ClienteController::class, 'buscarAjax'])->name('clientes.buscar.ajax');

    // ========== VENTAS ==========
    Route::prefix('ventas')->name('ventas.')->group(function () {
        Route::get('/', [VentaController::class, 'index'])->name('index');
        Route::get('/create', [VentaController::class, 'create'])->name('create');
        Route::post('/', [VentaController::class, 'store'])->name('store');
        Route::get('/{venta}', [VentaController::class, 'show'])->name('show');
        Route::get('/{venta}/ticket', [VentaController::class, 'ticket'])->name('ticket');
        Route::get('/{venta}/pdf', [VentaController::class, 'pdf'])->name('pdf');
        Route::post('/{venta}/anular', [VentaController::class, 'anular'])->name('anular');
        
        // Punto de venta
        Route::get('/punto/venta', [VentaController::class, 'puntoVenta'])->name('punto-venta');
        Route::post('/agregar-producto', [VentaController::class, 'agregarProducto'])->name('agregar-producto');
        
        // Estadísticas
        Route::get('/reporte/estadisticas', [VentaController::class, 'estadisticas'])->name('estadisticas');
    });

    // ========== INVENTARIO ==========
    Route::prefix('inventario')->name('inventario.')->group(function () {
        // Entradas
        Route::get('/entradas', [InventarioController::class, 'entradas'])->name('entradas');
        Route::get('/entradas/create', [InventarioController::class, 'createEntrada'])->name('entradas.create');
        Route::post('/entradas', [InventarioController::class, 'storeEntrada'])->name('entradas.store');
        
        // Salidas
        Route::get('/salidas', [InventarioController::class, 'salidas'])->name('salidas');
        Route::get('/salidas/create', [InventarioController::class, 'createSalida'])->name('salidas.create');
        Route::post('/salidas', [InventarioController::class, 'storeSalida'])->name('salidas.store');
        
        // Movimientos y Kardex
        Route::get('/movimientos', [InventarioController::class, 'movimientos'])->name('movimientos');
        Route::get('/kardex/{producto}', [InventarioController::class, 'kardex'])->name('kardex');
        
        // Ajuste
        Route::post('/ajuste', [InventarioController::class, 'ajuste'])->name('ajuste');
    });

    // ========== GASTOS ==========
    Route::resource('gastos', GastoController::class);
    Route::get('/gastos/reporte/estadisticas', [GastoController::class, 'estadisticas'])->name('gastos.estadisticas');

    // ========== REPORTES ==========
    Route::prefix('reportes')->name('reportes.')->group(function () {
        Route::get('/', [ReporteController::class, 'index'])->name('index');
        Route::get('/ventas', [ReporteController::class, 'ventas'])->name('ventas');
        Route::get('/inventario', [ReporteController::class, 'inventario'])->name('inventario');
        Route::get('/gastos', [ReporteController::class, 'gastos'])->name('gastos');
        Route::get('/utilidades', [ReporteController::class, 'utilidades'])->name('utilidades');
        Route::get('/clientes', [ReporteController::class, 'clientes'])->name('clientes');
        
        // PDF
        Route::get('/ventas/pdf', [ReporteController::class, 'pdfVentas'])->name('ventas.pdf');
        Route::get('/inventario/pdf', [ReporteController::class, 'pdfInventario'])->name('inventario.pdf');
    });

    // ========== USUARIOS (solo admin) ==========
    Route::middleware(['role:admin'])->group(function () {
        Route::resource('usuarios', UsuarioController::class);
        Route::post('/usuarios/{usuario}/activar', [UsuarioController::class, 'activar'])->name('usuarios.activar');
    });

    // ========== CONFIGURACIÓN ==========
    Route::prefix('configuracion')->name('configuracion.')->group(function () {
        Route::get('/', [ConfiguracionController::class, 'index'])->name('index');
        Route::put('/', [ConfiguracionController::class, 'update'])->name('update');
        Route::get('/backup', [ConfiguracionController::class, 'backup'])->name('backup');
        Route::post('/backup/descargar', [ConfiguracionController::class, 'descargarBackup'])->name('backup.descargar');
    });

});
