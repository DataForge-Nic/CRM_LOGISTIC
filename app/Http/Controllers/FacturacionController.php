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
        $clientes = Cliente::all();
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
        ]);

        Facturacion::create([
            'cliente_id'    => $request->cliente_id,
            'fecha_factura' => $request->fecha_factura,
            'numero_acta'   => $request->numero_acta,
            'monto_total'   => $request->monto_total,
            'moneda'        => $request->moneda,
            'tasa_cambio'   => $request->tasa_cambio,
            'monto_local'   => $request->monto_local,
            'estado_pago'   => $request->estado_pago,
            'nota'          => $request->nota,
            'created_by'    => Auth::id(),
        ]);

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
        $factura = \App\Models\Facturacion::with(['cliente'])->findOrFail($id);
        // Puedes agregar más relaciones si necesitas más datos
        $pdf = Pdf::loadView('facturacion.pdf', compact('factura'));
        return $pdf->download('factura_'.$factura->id.'.pdf');
    }

    public function previsualizarPDF($id)
    {
        $factura = \App\Models\Facturacion::with(['cliente'])->findOrFail($id);
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
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('facturacion.pdf', compact('factura'));
        return $pdf->stream('preview.pdf');
    }
}
