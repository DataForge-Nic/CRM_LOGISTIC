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
        $clientes = Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    // Formulario para crear cliente
    public function create()
    {
        return view('clientes.create');
    }

    // Guardar cliente nuevo
    public function store(Request $request)
    {
        $request->validate([
            'nombre_completo' => 'required|string|max:255',
            'correo'          => 'nullable|email',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'tipo_cliente'    => 'required|in:normal,casillero', // 🔁 CORREGIDO
        ]);

        Cliente::create([
            'nombre_completo' => $request->nombre_completo,
            'correo'          => $request->correo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'tipo_cliente'    => $request->tipo_cliente,
            'fecha_registro'  => now(),
            'created_by'      => Auth::id(),
        ]);

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
            'tipo_cliente'    => 'required|in:normal,casillero', // 🔁 CORREGIDO
        ]);

        $cliente->update([
            'nombre_completo' => $request->nombre_completo,
            'correo'          => $request->correo,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'tipo_cliente'    => $request->tipo_cliente,
            'updated_by'      => Auth::id(),
        ]);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Eliminar cliente
    public function destroy($id)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado correctamente.');
    }

    // Obtener datos de un cliente (API)
    public function show($id)
    {
        $cliente = Cliente::find($id);
        if (!$cliente) {
            return response()->json(['error' => 'Cliente no encontrado'], 404);
        }
        return response()->json($cliente);
    }
}
