@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Crear Usuario</h2>

    <form action="{{ route('usuarios.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control @error('nombre') is-invalid @enderror" value="{{ old('nombre') }}">
            @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Correo Electrónico</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}">
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Contraseña</label>
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="rol" class="form-label">Rol</label>
            <select name="rol" class="form-select @error('rol') is-invalid @enderror">
                <option value="admin" {{ old('rol') == 'admin' ? 'selected' : '' }}>Administrador</option>
                <option value="agente" {{ old('rol') == 'agente' ? 'selected' : '' }}>Agente</option>
            </select>
            @error('rol') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="form-check form-switch mb-3">
            <input class="form-check-input" type="checkbox" role="switch" name="estado" id="estado" value="1" {{ old('estado') ? 'checked' : '' }}>
            <label class="form-check-label" for="estado">Activo</label>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Usuario</button>
        <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
@endsection
