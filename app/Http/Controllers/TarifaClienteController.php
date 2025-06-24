<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TarifaCliente;

class TarifaClienteController extends Controller
{
    // Guardar nueva tarifa
    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'servicio_id' => 'required|exists:servicios,id',
            'tarifa' => 'required|numeric|min:0',
        ]);

        // Evitar duplicados
        $existe = TarifaCliente::where('cliente_id', $request->cliente_id)
            ->where('servicio_id', $request->servicio_id)
            ->first();
        if ($existe) {
            return redirect()->back()->with('error', 'Ya existe una tarifa para este servicio.');
        }

        TarifaCliente::create([
            'cliente_id' => $request->cliente_id,
            'servicio_id' => $request->servicio_id,
            'tarifa' => $request->tarifa,
        ]);

        return redirect()->back()->with('success', 'Tarifa agregada correctamente.');
    }

    // Eliminar tarifa
    public function destroy($id)
    {
        $tarifa = TarifaCliente::findOrFail($id);
        $tarifa->delete();
        return redirect()->back()->with('success', 'Tarifa eliminada correctamente.');
    }
} 