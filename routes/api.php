<?php

use App\Http\Controllers\FacturacionController;

Route::get('/facturacion/cliente-detalle/{clienteId}', [FacturacionController::class, 'clienteDetalle']); 