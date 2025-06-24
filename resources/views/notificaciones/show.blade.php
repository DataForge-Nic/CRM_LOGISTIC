@extends('layouts.app')

@section('title', 'Detalles de la Notificación - SkylinkOne CRM')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-10 col-md-12">
            <div class="card shadow border-0">
                <div class="card-header bg-white border-0 d-flex align-items-center">
                    <a href="{{ route('notificaciones.index') }}" class="text-primary me-3 text-decoration-none">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <h3 class="mb-0 fw-bold">Detalles de la Notificación</h3>
                </div>
                <div class="card-body">
                    <div class="mb-4">
                        <h4 class="fw-bold text-primary mb-1">{{ $notificacion->titulo }}</h4>
                        <div class="d-flex align-items-center gap-2 mb-2">
                            <span class="badge bg-light text-secondary border">
                                <i class="fas fa-calendar-alt me-1"></i>
                                Enviada el {{ \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y H:i') }}
                            </span>
                            <span class="badge rounded-pill bg-{{ $notificacion->leido ? 'success' : 'warning' }} {{ $notificacion->leido ? '' : 'text-dark' }}">
                                <i class="fas {{ $notificacion->leido ? 'fa-check' : 'fa-bell' }} me-1"></i>
                                {{ $notificacion->leido ? 'Leída' : 'No leída' }}
                            </span>
                        </div>
                        <div class="d-flex gap-2 mt-2">
                            <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-edit me-1"></i>Editar
                            </a>
                            <form action="{{ route('notificaciones.destroy', $notificacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta notificación?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i> Eliminar
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h6 class="fw-bold text-secondary mb-2">Información de la Notificación</h6>
                        <div class="row g-3">
                            <div class="col-6 col-md-4">
                                <div class="small text-muted">Estado</div>
                                <div class="fw-semibold">
                                    <i class="fas {{ $notificacion->leido ? 'fa-check text-success' : 'fa-bell text-warning' }} me-1"></i>
                                    {{ $notificacion->leido ? 'Leída' : 'No leída' }}
                                </div>
                            </div>
                            <div class="col-6 col-md-4">
                                <div class="small text-muted">Fecha de Creación</div>
                                <div class="fw-semibold">
                                    {{ \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y H:i:s') }}
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <div class="small text-muted">Destinatario</div>
                                <div class="fw-semibold">
                                    {{ $notificacion->usuario->name ?? 'Usuario no encontrado' }}<br>
                                    <span class="text-muted small">{{ $notificacion->usuario->email ?? '' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-2">
                        <h6 class="fw-bold text-secondary mb-2">Mensaje</h6>
                        <div class="bg-light rounded p-4" style="min-height: 120px; font-size: 1.25rem; line-height: 1.7;">
                            <span class="text-dark">{!! nl2br(e($notificacion->mensaje)) !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 