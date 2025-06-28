<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\InventarioController;
use App\Http\Controllers\NotificacionController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\TarifaClienteController;
use App\Models\Inventario;
use App\Models\Cliente;
use App\Models\User;
use App\Models\Facturacion;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LogInventarioController;

// Login
// Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
// Route::post('login', [AuthController::class, 'login']);
// Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Todas las rutas accesibles sin autenticación ni roles
// RUTA PRINCIPAL ÚNICA PARA DASHBOARD
Route::get('/', function () {
    $totalClientes = \App\Models\Cliente::count();
    $totalUsuarios = \App\Models\User::count();
    $totalFacturas = \App\Models\Facturacion::count();
    $totalPaquetes = \App\Models\Inventario::count();
    $ultimosPaquetes = \App\Models\Inventario::with(['cliente', 'servicio'])
        ->latest('fecha_ingreso')
        ->take(5)
        ->get();
    return view('welcome', compact('totalClientes', 'totalUsuarios', 'totalFacturas', 'totalPaquetes', 'ultimosPaquetes'));
})->name('welcome');

// Rutas para usuarios
Route::prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});

// Rutas para clientes
Route::prefix('clientes')->group(function () {
    Route::get('/', [ClienteController::class, 'index'])->name('clientes.index');
    Route::get('/crear', [ClienteController::class, 'create'])->name('clientes.create');
    Route::post('/', [ClienteController::class, 'store'])->name('clientes.store');
    Route::get('/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit');
    Route::put('/{id}', [ClienteController::class, 'update'])->name('clientes.update');
    Route::delete('/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy');
});

// Rutas para facturación
Route::prefix('facturacion')->group(function () {
    Route::get('/', [FacturacionController::class, 'index'])->name('facturacion.index');
    Route::get('/crear', [FacturacionController::class, 'create'])->name('facturacion.create');
    Route::post('/', [FacturacionController::class, 'store'])->name('facturacion.store');
    Route::get('/{id}/editar', [FacturacionController::class, 'edit'])->name('facturacion.edit');
    Route::put('/{id}', [FacturacionController::class, 'update'])->name('facturacion.update');
    Route::delete('/{id}', [FacturacionController::class, 'destroy'])->name('facturacion.destroy');
    Route::get('/{id}/pdf', [FacturacionController::class, 'descargarPDF'])->name('facturacion.pdf');
    Route::get('/{id}/preview', [FacturacionController::class, 'previsualizarPDF'])->name('facturacion.preview');
    Route::post('/preview-live', [FacturacionController::class, 'previewLivePDF'])->name('facturacion.preview-live');
    Route::get('/paquetes-por-cliente/{cliente}', [FacturacionController::class, 'paquetesPorCliente'])->name('facturacion.paquetes-por-cliente');
    Route::post('/{id}/cambiar-estado', [FacturacionController::class, 'cambiarEstado'])->name('facturacion.cambiar-estado');
});

// Rutas para inventario
Route::prefix('inventario')->group(function () {
    Route::get('/', [InventarioController::class, 'index'])->name('inventario.index');
    Route::get('/crear', [InventarioController::class, 'create'])->name('inventario.create');
    Route::post('/', [InventarioController::class, 'store'])->name('inventario.store');
    Route::get('/{id}/editar', [InventarioController::class, 'edit'])->name('inventario.edit');
    Route::put('/{id}', [InventarioController::class, 'update'])->name('inventario.update');
    Route::get('/{id}', [InventarioController::class, 'show'])->name('inventario.show');
    Route::post('obtener-tarifa', [InventarioController::class, 'obtenerTarifa'])->name('inventario.obtener-tarifa');
    Route::delete('/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy');
});

// Rutas para notificaciones
Route::prefix('notificaciones')->group(function () {
    Route::get('/', [NotificacionController::class, 'index'])->name('notificaciones.index');
    Route::get('/crear', [NotificacionController::class, 'create'])->name('notificaciones.create');
    Route::post('/', [NotificacionController::class, 'store'])->name('notificaciones.store');
    Route::get('/{notificacion}', [NotificacionController::class, 'show'])->name('notificaciones.show');
    Route::get('/{notificacion}/editar', [NotificacionController::class, 'edit'])->name('notificaciones.edit');
    Route::put('/{notificacion}', [NotificacionController::class, 'update'])->name('notificaciones.update');
    Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy');
    Route::patch('/{notificacion}/marcar-leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcar-leida');
    Route::get('/no-leidas', [NotificacionController::class, 'noLeidas'])->name('notificaciones.no-leidas');
    Route::patch('/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas-leidas');
});

// Rutas para tracking
Route::prefix('tracking')->group(function () {
    Route::get('/', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/dashboard', [TrackingController::class, 'dashboard'])->name('tracking.dashboard');
    Route::get('/crear', [TrackingController::class, 'create'])->name('tracking.create');
    Route::post('/', [TrackingController::class, 'store'])->name('tracking.store');
    Route::get('/{tracking}', [TrackingController::class, 'show'])->name('tracking.show');
    Route::get('/{tracking}/editar', [TrackingController::class, 'edit'])->name('tracking.edit');
    Route::put('/{tracking}', [TrackingController::class, 'update'])->name('tracking.update');
    Route::delete('/{tracking}', [TrackingController::class, 'destroy'])->name('tracking.destroy');
    Route::post('/{tracking}/actualizar-estado', [TrackingController::class, 'actualizarEstado'])->name('tracking.actualizar-estado');
    Route::get('/buscar', [TrackingController::class, 'buscarPorCodigo'])->name('tracking.buscar');
    Route::get('/proximos-vencer', [TrackingController::class, 'proximosVencer'])->name('tracking.proximos-vencer');
    Route::get('/verificar-recordatorios', [TrackingController::class, 'verificarRecordatorios'])->name('tracking.verificar-recordatorios');
});

// Tarifas
Route::post('tarifas-clientes', [TarifaClienteController::class, 'store'])->name('tarifas-clientes.store');
Route::delete('tarifas-clientes/{id}', [TarifaClienteController::class, 'destroy'])->name('tarifas-clientes.destroy');

// Historial de inventario
Route::get('logs-inventario', [LogInventarioController::class, 'index'])->name('logs_inventario.index');