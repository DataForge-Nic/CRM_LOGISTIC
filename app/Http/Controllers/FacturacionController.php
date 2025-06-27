<?php

namespace App\Http\Controllers;

use App\Models\Facturacion;
use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class FacturacionController extends Controller
{
    // Listar facturas
    public function index()
    {
        $facturas = Facturacion::with('cliente')->latest()->get();
        return view('facturacion.index', compact('facturas'));
    }

    // Mostrar formulario de creación
    public function create()
    {
        $clientes = \App\Models\Cliente::select('id', 'nombre_completo')->orderBy('nombre_completo')->get();
        return view('facturacion.create', compact('clientes'));
    }

    // Guardar nueva factura
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'     => 'required|exists:clientes,id',
            'fecha_factura'  => 'required|date',
            'numero_acta'    => 'nullable|string|max:100',
            'monto_total'    => 'required|numeric',
            'moneda'         => 'required|in:USD,NIO',
            'tasa_cambio'    => 'nullable|numeric',
            'monto_local'    => 'required|numeric',
            'estado_pago'    => 'required|in:pendiente,parcial,pagado',
            'nota'           => 'nullable|string',
            'paquetes'       => 'required|array|min:1',
            'paquetes.*'     => 'exists:inventario,id',
            'delivery'       => 'nullable|numeric',
        ]);

        // Validar que los paquetes pertenezcan al cliente y no estén facturados
        $paquetes = \App\Models\Inventario::whereIn('id', $request->paquetes)
            ->where('cliente_id', $request->cliente_id)
            ->whereNull('factura_id')
            ->pluck('id')->toArray();
        if (count($paquetes) !== count($request->paquetes)) {
            return back()->withErrors(['paquetes' => 'Uno o más paquetes seleccionados no pertenecen al cliente o ya han sido facturados.'])->withInput();
        }

        $factura = Facturacion::create([
            'cliente_id'    => $request->cliente_id,
            'fecha_factura' => $request->fecha_factura,
            'numero_acta'   => $request->numero_acta,
            'monto_total'   => $request->monto_total,
            'moneda'        => $request->moneda,
            'tasa_cambio'   => $request->tasa_cambio,
            'monto_local'   => $request->monto_local,
            'estado_pago'   => $request->estado_pago,
            'nota'          => $request->nota,
            'delivery'      => $request->delivery,
            'created_by'    => Auth::id(),
        ]);

        // Asociar paquetes seleccionados a la factura
        \App\Models\Inventario::whereIn('id', $paquetes)->update(['factura_id' => $factura->id]);

        return redirect()->route('facturacion.index')->with('success', 'Factura registrada correctamente.');
    }

    // Editar factura
    public function edit($id)
    {
        $factura = Facturacion::findOrFail($id);
        $clientes = Cliente::all();
        return view('facturacion.edit', compact('factura', 'clientes'));
    }

    // Actualizar factura
    public function update(Request $request, $id)
    {
        $factura = Facturacion::findOrFail($id);

        $request->validate([
            'cliente_id'     => 'required|exists:clientes,id',
            'fecha_factura'  => 'required|date',
            'numero_acta'    => 'nullable|string|max:100',
            'monto_total'    => 'required|numeric',
            'moneda'         => 'required|in:USD,NIO',
            'tasa_cambio'    => 'nullable|numeric',
            'monto_local'    => 'required|numeric',
            'estado_pago'    => 'required|in:pendiente,parcial,pagado',
            'nota'           => 'nullable|string',
            'delivery'       => 'nullable|numeric',
        ]);

        $factura->update([
            'cliente_id'    => $request->cliente_id,
            'fecha_factura' => $request->fecha_factura,
            'numero_acta'   => $request->numero_acta,
            'monto_total'   => $request->monto_total,
            'moneda'        => $request->moneda,
            'tasa_cambio'   => $request->tasa_cambio,
            'monto_local'   => $request->monto_local,
            'estado_pago'   => $request->estado_pago,
            'nota'          => $request->nota,
            'delivery'      => $request->delivery,
            'updated_by'    => Auth::id(),
        ]);

        return redirect()->route('facturacion.index')->with('success', 'Factura actualizada.');
    }

    // Eliminar factura
    public function destroy($id)
    {
        $factura = Facturacion::findOrFail($id);
        $factura->delete();

        return redirect()->route('facturacion.index')->with('success', 'Factura eliminada.');
    }

    public function descargarPDF($id)
    {
        $factura = \App\Models\Facturacion::with(['cliente', 'paquetes.servicio'])->findOrFail($id);
        // Puedes agregar más relaciones si necesitas más datos
        $pdf = Pdf::loadView('facturacion.pdf', compact('factura'));
        return $pdf->download('factura_'.$factura->id.'.pdf');
    }

    public function previsualizarPDF($id)
    {
        $factura = \App\Models\Facturacion::with(['cliente', 'paquetes.servicio'])->findOrFail($id);
        $pdf = Pdf::loadView('facturacion.pdf', compact('factura'));
        return $pdf->stream('factura_'.$factura->id.'.pdf');
    }

    public function previewLivePDF(Request $request)
    {
        // Crear un objeto temporal con los datos recibidos
        $factura = new \App\Models\Facturacion($request->all());
        // Simular relación con cliente
        $factura->cliente = (object) [
            'nombre_completo' => $request->input('cliente_nombre', ''),
            'direccion' => $request->input('cliente_direccion', ''),
            'telefono' => $request->input('cliente_telefono', ''),
        ];
        $paquetes = [];
        // Si no se reciben paquetes pero hay cliente_id, traer todos los paquetes no facturados
        if (!$request->has('paquetes') && $request->filled('cliente_id')) {
            $inventarios = \App\Models\Inventario::where('cliente_id', $request->input('cliente_id'))
                ->whereNull('factura_id')
                ->with('servicio')
                ->get();
            foreach ($inventarios as $inv) {
                $paquetes[] = (object) [
                    'numero_guia' => $inv->numero_guia,
                    'notas' => $inv->notas,
                    'tracking_codigo' => $inv->tracking_codigo,
                    'servicio' => $inv->servicio ? $inv->servicio->tipo_servicio : null,
                    'tarifa_manual' => $inv->tarifa_manual,
                    'monto_calculado' => $inv->monto_calculado,
                    'peso_lb' => $inv->peso_lb,
                ];
            }
        }
        // Solo agregar paquetes seleccionados, no duplicar
        if ($request->has('paquetes')) {
            foreach ($request->input('paquetes', []) as $i => $id) {
                $paquetes[] = [
                    'numero_guia' => $request->input('paquete_guia_'.$id, ''),
                    'notas' => $request->input('paquete_descripcion_'.$id, ''),
                    'tracking_codigo' => $request->input('paquete_tracking_'.$id, ''),
                    'servicio' => $request->input('paquete_servicio_'.$id, ''),
                    'tarifa_manual' => $request->input('paquete_tarifa_'.$id, 0),
                    'monto_calculado' => $request->input('paquete_valor_'.$id, 0),
                    'peso_lb' => $request->input('paquete_peso_'.$id, ''),
                ];
            }
        }
        $factura->paquetes = collect($paquetes);
        $factura->delivery = $request->input('delivery', 0);
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('facturacion.pdf', compact('factura'));
        return $pdf->stream('preview.pdf');
    }

    /**
     * Retorna los productos del inventario de un cliente para facturación (AJAX)
     */
    public function paquetesPorCliente($clienteId)
    {
        $inventarios = \App\Models\Inventario::where('cliente_id', $clienteId)
            ->whereNull('factura_id')
            ->with('servicio')
            ->get()
            ->map(function($inv) {
                return [
                    'id' => $inv->id,
                    'numero_guia' => $inv->numero_guia,
                    'notas' => $inv->notas,
                    'tracking_codigo' => $inv->tracking_codigo,
                    'servicio' => $inv->servicio ? $inv->servicio->tipo_servicio : null,
                    'tarifa_manual' => $inv->tarifa_manual,
                    'monto_calculado' => $inv->monto_calculado,
                    'peso_lb' => $inv->peso_lb,
                ];
            });
        return response()->json($inventarios);
    }

    /**
     * API: Retorna datos del cliente, historial de facturas y paquetes no facturados
     */
    public function clienteDetalle($clienteId)
    {
        $cliente = \App\Models\Cliente::select('id', 'nombre_completo', 'telefono', 'direccion')->findOrFail($clienteId);
        $paquetes = \App\Models\Inventario::where('cliente_id', $clienteId)
            ->whereNull('factura_id')
            ->with('servicio')
            ->get()
            ->map(function($inv) {
                return [
                    'id' => $inv->id,
                    'numero_guia' => $inv->numero_guia,
                    'notas' => $inv->notas,
                    'tracking_codigo' => $inv->tracking_codigo,
                    'servicio' => $inv->servicio ? $inv->servicio->tipo_servicio : null,
                    'tarifa_manual' => $inv->tarifa_manual,
                    'monto_calculado' => $inv->monto_calculado,
                    'peso_lb' => $inv->peso_lb,
                ];
            });
        $historial = \App\Models\Facturacion::where('cliente_id', $clienteId)
            ->orderByDesc('fecha_factura')
            ->take(5)
            ->get(['id', 'fecha_factura', 'monto_total', 'estado_pago']);
        return response()->json([
            'success' => true,
            'cliente' => $cliente,
            'paquetes' => $paquetes,
            'historial' => $historial,
        ]);
    }
}
