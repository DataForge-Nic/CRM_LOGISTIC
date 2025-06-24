@extends('layouts.app')

@section('title', 'Nueva Notificación - SkylinkOne CRM')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7 col-md-9">
            <div class="card shadow border-0">
                <div class="card-header bg-white border-0 pb-0 d-flex align-items-center">
                    <a href="{{ route('notificaciones.index') }}" class="text-primary me-3 text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <h3 class="mb-0 fw-bold">Nueva Notificación</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('notificaciones.store') }}" method="POST" autocomplete="off">
                        @csrf
                        <div class="mb-4">
                            <label for="user_id" class="form-label fw-semibold">
                                <i class="fas fa-user me-1"></i>Usuario Destinatario <span class="text-danger">*</span>
                            </label>
                            <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                                <option value="">Selecciona un usuario</option>
                                @foreach($usuarios as $usuario)
                                    <option value="{{ $usuario->id }}" {{ old('user_id') == $usuario->id ? 'selected' : '' }}>
                                        {{ $usuario->name }} ({{ $usuario->email }})
                                    </option>
                                @endforeach
                            </select>
                            @error('user_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="titulo" class="form-label fw-semibold">
                                <i class="fas fa-heading me-1"></i>Título <span class="text-danger">*</span>
                            </label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" class="form-control @error('titulo') is-invalid @enderror" placeholder="Ingresa el título de la notificación" required maxlength="255">
                            @error('titulo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label for="mensaje" class="form-label fw-semibold">
                                <i class="fas fa-align-left me-1"></i>Mensaje <span class="text-danger">*</span>
                            </label>
                            <textarea name="mensaje" id="mensaje" rows="5" class="form-control @error('mensaje') is-invalid @enderror" placeholder="Ingresa el mensaje de la notificación" required>{{ old('mensaje') }}</textarea>
                            @error('mensaje')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <a href="{{ route('notificaciones.index') }}" class="btn btn-link text-secondary">
                                <i class="fas fa-times me-1"></i>Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary px-4 shadow">
                                <i class="fas fa-paper-plane me-2"></i>Crear Notificación
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 