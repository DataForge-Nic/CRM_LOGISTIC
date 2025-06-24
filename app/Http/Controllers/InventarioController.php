<?php

namespace App\Http\Controllers;

use App\Models\Inventario;
use App\Models\Cliente;
use App\Models\Servicio;
use Illuminate\Http\Request;

class InventarioController extends Controller
{
    public function index()
    {
        $inventarios = Inventario::with(['cliente', 'servicio'])->latest()->paginate(10);
        return view('inventario.index', compact('inventarios'));
    }

    public function create()
    {
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        return view('inventario.create', compact('clientes', 'servicios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
            'peso_lb'         => 'nullable|numeric',
            'volumen_pie3'    => 'nullable|numeric',
            'tarifa_manual'   => 'nullable|numeric',
            'estado'          => 'required|string|max:50',
            'numero_guia'     => 'nullable|string|max:100',
            'notas'           => 'nullable|string',
            'servicio_id'     => 'nullable|exists:servicios,id',
        ]);

        $data = $request->all();
        $data['fecha_ingreso'] = now();

        // Cálculo automático del monto
        $peso = floatval($data['peso_lb'] ?? 0);
        $volumen = floatval($data['volumen_pie3'] ?? 0);
        $tarifaBase = 1.00; // cambiar según tarifa real

        $data['monto_calculado'] = $data['tarifa_manual'] ?? ($tarifaBase * max($peso, $volumen));

        Inventario::create($data);

        return redirect()->route('inventario.index')->with('success', 'Paquete registrado correctamente.');
    }

    public function edit($id)
    {
        $inventario = Inventario::findOrFail($id);
        $clientes = Cliente::all();
        $servicios = Servicio::all();
        return view('inventario.edit', [
            'paquete' => $inventario,
            'clientes' => $clientes,
            'servicios' => $servicios
        ]);
    }

    public function update(Request $request, $id)
    {
        $inventario = Inventario::findOrFail($id);

        $request->validate([
            'cliente_id'      => 'required|exists:clientes,id',
            'peso_lb'         => 'nullable|numeric',
            'volumen_pie3'    => 'nullable|numeric',
            'tarifa_manual'   => 'nullable|numeric',
            'estado'          => 'required|string|max:50',
            'numero_guia'     => 'nullable|string|max:100',
            'notas'           => 'nullable|string',
            'servicio_id'     => 'nullable|exists:servicios,id',
        ]);

        $data = $request->all();

        // Recalcular monto
        $peso = floatval($data['peso_lb'] ?? 0);
        $volumen = floatval($data['volumen_pie3'] ?? 0);
        $tarifaBase = 1.00;

        $data['monto_calculado'] = $data['tarifa_manual'] ?? ($tarifaBase * max($peso, $volumen));

        $inventario->update($data);

        return redirect()->route('inventario.index')->with('success', 'Paquete actualizado correctamente.');
    }

    public function destroy($id)
    {
        $inventario = Inventario::findOrFail($id);
        $inventario->delete();

        return redirect()->route('inventario.index')->with('success', 'Paquete eliminado.');
    }

    public function show($id)
    {
        $paquete = \App\Models\Inventario::with(['cliente', 'servicio', 'factura'])->findOrFail($id);
        return view('inventario.show', compact('paquete'));
    }

    // Endpoint para obtener tarifa de cliente y servicio (AJAX)
    public function obtenerTarifa(Request $request)
    {
        $clienteId = $request->input('cliente_id');
        $servicioId = $request->input('servicio_id');
        $tarifa = \App\Models\TarifaCliente::where('cliente_id', $clienteId)
            ->where('servicio_id', $servicioId)
            ->first();
        return response()->json(['tarifa' => $tarifa ? $tarifa->tarifa : null]);
    }
}
