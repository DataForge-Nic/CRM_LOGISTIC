@extends('layouts.app')

@section('title', 'Editar Cliente - SkylinkOne CRM')
@section('page-title', 'Editar Cliente')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-user-edit text-primary me-2"></i>
                        Editar Cliente
                    </h1>
                    <p class="text-muted mb-0">Modifica los datos del cliente seleccionado</p>
                </div>
                <a href="{{ route('clientes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card p-4">
                <form action="{{ route('clientes.update', $cliente->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-3">
                        <label for="nombre_completo" class="form-label fw-semibold">Nombre completo</label>
                        <input type="text" name="nombre_completo" class="form-control" value="{{ old('nombre_completo', $cliente->nombre_completo) }}">
                        @error('nombre_completo') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="correo" class="form-label fw-semibold">Correo</label>
                        <input type="email" name="correo" class="form-control" value="{{ old('correo', $cliente->correo) }}">
                        @error('correo') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                        <input type="text" name="telefono" class="form-control" value="{{ old('telefono', $cliente->telefono) }}">
                        @error('telefono') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="direccion" class="form-label fw-semibold">Dirección</label>
                        <textarea name="direccion" class="form-control">{{ old('direccion', $cliente->direccion) }}</textarea>
                        @error('direccion') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="mb-3">
                        <label for="tipo_cliente" class="form-label fw-semibold">Tipo de Cliente</label>
                        <select name="tipo_cliente" class="form-select">
                            <option value="normal" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'normal' ? 'selected' : '' }}>Normal</option>
                            <option value="casillero" {{ old('tipo_cliente', $cliente->tipo_cliente) == 'casillero' ? 'selected' : '' }}>Casillero</option>
                        </select>
                        @error('tipo_cliente') <div class="text-danger">{{ $message }}</div> @enderror
                    </div>
                    <div class="d-flex gap-2 justify-content-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Actualizar
                        </button>
                        <a href="{{ route('clientes.index') }}" class="btn btn-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
