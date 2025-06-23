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
            <label for="volumen_pie3" class="form-label">Volumen (ft³)</label>
            <input type="number" step="0.01" name="volumen_pie3" class="form-control" value="{{ $paquete->volumen_pie3 }}">
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
