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
Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Agrupo todas las rutas protegidas por autenticación
Route::middleware('auth')->group(function () {
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
    })->name('welcome')->middleware('role:admin,contador,agente');

    // Rutas para usuarios (solo admin)
    Route::prefix('usuarios')->middleware('role:admin')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('usuarios.index');
        Route::get('/crear', [UserController::class, 'create'])->name('usuarios.create');
        Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
        Route::get('/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('usuarios.update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
    });

    // Rutas para clientes
    Route::prefix('clientes')->group(function () {
        Route::get('/', [ClienteController::class, 'index'])->name('clientes.index')->middleware('role:admin,contador,agente');
        Route::get('/crear', [ClienteController::class, 'create'])->name('clientes.create')->middleware('role:admin,agente');
        Route::post('/', [ClienteController::class, 'store'])->name('clientes.store')->middleware('role:admin,agente');
        Route::get('/{id}/editar', [ClienteController::class, 'edit'])->name('clientes.edit')->middleware('role:admin,agente');
        Route::put('/{id}', [ClienteController::class, 'update'])->name('clientes.update')->middleware('role:admin,agente');
        Route::delete('/{id}', [ClienteController::class, 'destroy'])->name('clientes.destroy')->middleware('role:admin,agente');
    });

    // Rutas para facturación (admin y contador)
    Route::prefix('facturacion')->middleware('role:admin,contador')->group(function () {
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
    });

    // Rutas para inventario (admin y agente)
    Route::prefix('inventario')->middleware('role:admin,agente')->group(function () {
        Route::get('/', [InventarioController::class, 'index'])->name('inventario.index');
        Route::get('/crear', [InventarioController::class, 'create'])->name('inventario.create');
        Route::post('/', [InventarioController::class, 'store'])->name('inventario.store');
        Route::get('/{id}/editar', [InventarioController::class, 'edit'])->name('inventario.edit');
        Route::put('/{id}', [InventarioController::class, 'update'])->name('inventario.update');
        Route::get('/{id}', [InventarioController::class, 'show'])->name('inventario.show');
        Route::post('obtener-tarifa', [InventarioController::class, 'obtenerTarifa'])->name('inventario.obtener-tarifa');
    });
    Route::delete('inventario/{id}', [InventarioController::class, 'destroy'])->name('inventario.destroy')->middleware(['auth', 'role:admin']);

    // Rutas para notificaciones (todos pueden ver, solo admin y agente pueden crear/editar/eliminar)
    Route::prefix('notificaciones')->group(function () {
        Route::get('/', [NotificacionController::class, 'index'])->name('notificaciones.index')->middleware('role:admin,contador,agente');
        Route::get('/crear', [NotificacionController::class, 'create'])->name('notificaciones.create')->middleware('role:admin,agente');
        Route::post('/', [NotificacionController::class, 'store'])->name('notificaciones.store')->middleware('role:admin,agente');
        Route::get('/{notificacion}', [NotificacionController::class, 'show'])->name('notificaciones.show')->middleware('role:admin,contador,agente');
        Route::get('/{notificacion}/editar', [NotificacionController::class, 'edit'])->name('notificaciones.edit')->middleware('role:admin,agente');
        Route::put('/{notificacion}', [NotificacionController::class, 'update'])->name('notificaciones.update')->middleware('role:admin,agente');
        Route::delete('/{notificacion}', [NotificacionController::class, 'destroy'])->name('notificaciones.destroy')->middleware('role:admin,agente');
        Route::patch('/{notificacion}/marcar-leida', [NotificacionController::class, 'marcarLeida'])->name('notificaciones.marcar-leida')->middleware('role:admin,contador,agente');
        Route::get('/no-leidas', [NotificacionController::class, 'noLeidas'])->name('notificaciones.no-leidas')->middleware('role:admin,contador,agente');
        Route::patch('/marcar-todas-leidas', [NotificacionController::class, 'marcarTodasLeidas'])->name('notificaciones.marcar-todas-leidas')->middleware('role:admin,contador,agente');
    });

    // Rutas para tracking (admin y agente)
    Route::prefix('tracking')->middleware('role:admin,agente')->group(function () {
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

    // Tarifas solo para admin
    Route::post('tarifas-clientes', [TarifaClienteController::class, 'store'])->name('tarifas-clientes.store')->middleware('role:admin');
    Route::delete('tarifas-clientes/{id}', [TarifaClienteController::class, 'destroy'])->name('tarifas-clientes.destroy')->middleware('role:admin');

    // Historial de inventario (solo admin)
    Route::get('logs-inventario', [LogInventarioController::class, 'index'])->name('logs_inventario.index')->middleware(['auth', 'role:admin']);
});