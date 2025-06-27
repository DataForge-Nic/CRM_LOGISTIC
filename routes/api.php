<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FacturacionController;
use App\Http\Controllers\ClienteController;

Route::get('/facturacion/cliente-detalle/{clienteId}', [FacturacionController::class, 'clienteDetalle']);
Route::get('/clientes/{id}', [FacturacionController::class, 'clienteDetalle']);
Route::get('/prueba', function() {
    return response()->json(['ok' => true]);
}); 