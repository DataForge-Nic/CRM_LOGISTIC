<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('usuarios')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('usuarios.index');
    Route::get('/crear', [UserController::class, 'create'])->name('usuarios.create');
    Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
    Route::get('/{id}/editar', [UserController::class, 'edit'])->name('usuarios.edit');
    Route::put('/{id}', [UserController::class, 'update'])->name('usuarios.update');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');
});