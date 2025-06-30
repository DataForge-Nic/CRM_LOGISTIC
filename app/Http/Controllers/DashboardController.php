<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Inventario;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function estadisticasPaquetes(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');
        $query = Inventario::with('servicio');
        if ($desde && $hasta) {
            $query->whereBetween('fecha_ingreso', [$desde, $hasta]);
        }
        $paquetes = $query->get();
        $maritimo = $paquetes->filter(function($p) {
            return strtolower($p->servicio->tipo_servicio ?? '') === 'maritimo';
        });
        $aereo = $paquetes->filter(function($p) {
            return strtolower($p->servicio->tipo_servicio ?? '') === 'aereo';
        });
        return response()->json([
            'maritimo' => [
                'cantidad' => $maritimo->count(),
                'libras' => $maritimo->sum('peso_lb'),
                'dinero' => $maritimo->sum('monto_calculado'),
            ],
            'aereo' => [
                'dinero' => $aereo->sum('monto_calculado'),
            ],
            'total_paquetes' => $paquetes->count(),
        ]);
    }

    public function estadisticasPaquetesCliente(Request $request)
    {
        $clienteId = $request->input('cliente_id');
        $tipoServicio = $request->input('tipo_servicio', 'todos');
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        $query = \App\Models\Inventario::with('servicio')
            ->where('cliente_id', $clienteId);
        if ($desde && $hasta) {
            $query->whereBetween('fecha_ingreso', [$desde, $hasta]);
        }
        $paquetes = $query->get();
        // Normaliza tipo de servicio
        function normalizarServicio($tipo) {
            $tipo = strtolower($tipo ?? '');
            $tipo = str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $tipo);
            return $tipo;
        }
        if ($tipoServicio === 'aereo') {
            $paquetes = $paquetes->filter(function($p) {
                return normalizarServicio($p->servicio->tipo_servicio ?? '') === 'aereo';
            });
        } elseif ($tipoServicio === 'maritimo') {
            $paquetes = $paquetes->filter(function($p) {
                return normalizarServicio($p->servicio->tipo_servicio ?? '') === 'maritimo';
            });
        }
        $total = $paquetes->count();
        $dinero = $paquetes->sum('monto_calculado');
        $libras = $paquetes->sum('peso_lb');
        return response()->json([
            'total' => $total,
            'dinero' => $dinero,
            'libras' => $libras,
        ]);
    }
} 