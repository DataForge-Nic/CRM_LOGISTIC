@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Editar Paquete del Inventario</h2>

    <form action="{{ route('inventario.update', $paquete->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="cliente_id" class="form-label">Cliente</label>
            <select name="cliente_id" class="form-select" required>
                @foreach($clientes as $cliente)
                    <option value="{{ $cliente->id }}" {{ $paquete->cliente_id == $cliente->id ? 'selected' : '' }}>
                        {{ $cliente->nombre_completo }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="servicio_id" class="form-label">Servicio</label>
            <select name="servicio_id" class="form-select" required>
                @foreach($servicios as $servicio)
                    <option value="{{ $servicio->id }}" {{ $paquete->servicio_id == $servicio->id ? 'selected' : '' }}>
                        {{ $servicio->tipo_servicio }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="peso_lb" class="form-label">Peso (lb)</label>
            <input type="number" step="0.01" name="peso_lb" class="form-control" value="{{ $paquete->peso_lb }}">
        </div>

        <div class="mb-3">
            <label for="tracking_codigo" class="form-label">Código de Tracking</label>
            <input type="text" name="tracking_codigo" class="form-control" value="{{ $paquete->tracking_codigo }}">
        </div>

        <div class="mb-3">
            <label for="tarifa_manual" class="form-label">Tarifa Manual (opcional)</label>
            <input type="number" step="0.01" name="tarifa_manual" class="form-control" value="{{ $paquete->tarifa_manual }}">
        </div>

        <div class="mb-3">
            <label for="numero_guia" class="form-label">Número de Guía</label>
            <input type="text" name="numero_guia" class="form-control" value="{{ $paquete->numero_guia }}">
        </div>

        <div class="mb-3">
            <label for="estado" class="form-label">Estado</label>
            <select name="estado" class="form-select">
                <option value="recibido" {{ $paquete->estado == 'recibido' ? 'selected' : '' }}>Recibido</option>
                <option value="en_transito" {{ $paquete->estado == 'en_transito' ? 'selected' : '' }}>En Tránsito</option>
                <option value="entregado" {{ $paquete->estado == 'entregado' ? 'selected' : '' }}>Entregado</option>
                <option value="en_oficina" {{ $paquete->estado == 'en_oficina' ? 'selected' : '' }}>En Oficina</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="notas" class="form-label">Notas</label>
            <textarea name="notas" class="form-control">{{ $paquete->notas }}</textarea>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.querySelector('select[name="cliente_id"]');
    const servicioSelect = document.querySelector('select[name="servicio_id"]');
    const tarifaManualInput = document.querySelector('input[name="tarifa_manual"]');
    const pesoInput = document.querySelector('input[name="peso_lb"]');
    const volumenInput = document.querySelector('input[name="volumen_pie3"]');

    function obtenerTarifaCliente() {
        const clienteId = clienteSelect.value;
        const servicioId = servicioSelect.value;
        if (clienteId && servicioId) {
            fetch("{{ route('inventario.obtener-tarifa') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ cliente_id: clienteId, servicio_id: servicioId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.tarifa !== null) {
                    tarifaManualInput.value = data.tarifa;
                } else {
                    tarifaManualInput.value = '';
                }
            });
        } else {
            tarifaManualInput.value = '';
        }
    }

    clienteSelect.addEventListener('change', obtenerTarifaCliente);
    servicioSelect.addEventListener('change', obtenerTarifaCliente);
});
</script>
@endsection
