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

        \Log::info('Filtro dashboard', [
            'cliente_id' => $clienteId,
            'tipo_servicio' => $tipoServicio,
            'desde' => $desde,
            'hasta' => $hasta,
        ]);

        $query = \App\Models\Inventario::with('servicio')
            ->where('cliente_id', $clienteId);
        if ($desde && $hasta) {
            $query->whereBetween('fecha_ingreso', [$desde, $hasta]);
        }
        if ($tipoServicio !== 'todos') {
            $tipoServicioNormalizado = strtolower(str_replace(['á','é','í','ó','ú'], ['a','e','i','o','u'], $tipoServicio));
            $query->whereHas('servicio', function($q) use ($tipoServicioNormalizado) {
                $q->whereRaw(
                    "LOWER(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(tipo_servicio, 'á', 'a'), 'é', 'e'), 'í', 'i'), 'ó', 'o'), 'ú', 'u')) = ?",
                    [$tipoServicioNormalizado]
                );
            });
        }
        $paquetes = $query->get();
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