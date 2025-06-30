<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClienteController extends Controller
{
    // Mostrar lista de clientes
    public function index()
    {
        $clientes = Cliente::orderBy('nombre_completo')->paginate(10);
        return view('clientes.index', compact('clientes'));
    }

    // Formulario para crear cliente
    public function create()
    {
        $servicios = \App\Models\Servicio::all();
        return view('clientes.create', compact('servicios'));
    }

    // Guardar cliente nuevo
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'nullable|email',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'tipo_cliente'    => 'required|in:normal,casillero,Normal,VIP',
        ]);

        $cliente = Cliente::create([
            'nombre_completo' => $request->nombre_completo,
            'correo'          => $request->correo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'tipo_cliente'    => $request->tipo_cliente,
            'fecha_registro'  => now(),
            'created_by'      => Auth::id(),
        ]);

        // Guardar tarifas
        $servicios = \App\Models\Servicio::all();
        $map = [
            'aereo' => 'tarifa_aereo',
            'maritimo' => 'tarifa_maritimo',
            'express' => 'tarifa_express',
        ];
        foreach ($map as $tipo => $input) {
            $servicio = $servicios->first(function($s) use ($tipo) {
                return strtolower($s->tipo_servicio) === $tipo;
            });
            $valor = $request->input($input);
            if ($servicio && $valor !== null && $valor !== '') {
                \App\Models\TarifaCliente::updateOrCreate([
                    'cliente_id' => $cliente->id,
                    'servicio_id' => $servicio->id,
                ], [
                    'tarifa' => $valor,
                ]);
            }
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['id' => $cliente->id]);
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente creado correctamente.');
    }

    // Formulario para editar cliente
    public function edit($id)
    {
        $cliente = Cliente::findOrFail($id);
        $tarifas = \App\Models\TarifaCliente::where('cliente_id', $id)->with('servicio')->get();
        $servicios = \App\Models\Servicio::all();
        return view('clientes.edit', compact('cliente', 'tarifas', 'servicios'));
    }

    // Actualizar cliente
    public function update(Request $request, $id)
    {
        $cliente = Cliente::findOrFail($id);

        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'nullable|email',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'tipo_cliente'    => 'required|in:normal,casillero',
        ]);

        $cliente->update([
            'nombre_completo' => $request->nombre_completo,
            'correo'          => $request->correo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'tipo_cliente'    => $request->tipo_cliente,
            'updated_by'      => Auth::id(),
        ]);

        // Guardar o actualizar tarifas
        $servicios = \App\Models\Servicio::all();
        $map = [
            'aereo' => 'tarifa_aereo',
            'maritimo' => 'tarifa_maritimo',
            'express' => 'tarifa_express',
        ];
        foreach ($map as $tipo => $input) {
            $servicio = $servicios->first(function($s) use ($tipo) {
                return strtolower($s->tipo_servicio) === $tipo;
            });
            $valor = $request->input($input);
            if ($servicio && $valor !== null && $valor !== '') {
                \App\Models\TarifaCliente::updateOrCreate([
                    'cliente_id' => $cliente->id,
                    'servicio_id' => $servicio->id,
                ], [
                    'tarifa' => $valor,
                ]);
            }
        }

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Eliminar cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    // Previsualización de cliente
    public function show($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $tarifas = \App\Models\TarifaCliente::where('cliente_id', $id)->with('servicio')->get();
        $servicios = \App\Models\Servicio::all();
        return view('clientes.show', compact('cliente', 'tarifas', 'servicios'));
    }
}
