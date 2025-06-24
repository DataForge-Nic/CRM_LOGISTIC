<?php

namespace App\Http\Controllers;

use App\Models\LogInventario;
use Illuminate\Support\Facades\Auth;

class LogInventarioController extends Controller
{
    public function index()
    {
        // Solo admin puede ver
        $user = Auth::user();
        if (!$user || $user->rol !== 'admin') {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        $logs = \App\Models\LogInventario::with(['usuario', 'inventario'])
            ->when(request('agente'), function($q) {
                $q->whereHas('usuario', function($q2) {
                    $q2->where('nombre', 'like', '%'.request('agente').'%');
                });
            })
            ->when(request('accion'), function($q) {
                $q->where('accion', request('accion'));
            })
            ->when(request('warehouse'), function($q) {
                $q->whereHas('inventario', function($q2) {
                    $q2->where('numero_guia', 'like', '%'.request('warehouse').'%');
                });
            })
            ->when(request('desde'), function($q) {
                $q->whereDate('created_at', '>=', request('desde'));
            })
            ->when(request('hasta'), function($q) {
                $q->whereDate('created_at', '<=', request('hasta'));
            })
            ->latest()->paginate(20);
        return view('logs_inventario.index', compact('logs'));
    }
} 