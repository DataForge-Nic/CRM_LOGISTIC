@extends('layouts.app')

@section('title', 'Detalles de la Notificación - SkylinkOne CRM')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-bell text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Detalles de la Notificación</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Vista detallada de la notificación seleccionada</p>
                    </div>
                </div>
                <a href="{{ route('notificaciones.index') }}" class="btn btn-outline-light fw-semibold shadow-sm px-4">
                    <i class="fas fa-arrow-left me-2"></i> Volver a Notificaciones
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center justify-content-between">
                    <div>
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
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-outline-primary">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <form action="{{ route('notificaciones.destroy', $notificacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta notificación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4 mb-4">
                        <div class="col-md-4">
                            <div class="small text-muted">Estado</div>
                            <div class="fw-semibold">
                                <i class="fas {{ $notificacion->leido ? 'fa-check text-success' : 'fa-bell text-warning' }} me-1"></i>
                                {{ $notificacion->leido ? 'Leída' : 'No leída' }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Fecha de Creación</div>
                            <div class="fw-semibold">
                                {{ \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y H:i:s') }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="small text-muted">Destinatario</div>
                            <div class="fw-semibold">
                                {{ $notificacion->usuario->name ?? 'Usuario no encontrado' }}<br>
                                <span class="text-muted small">{{ $notificacion->usuario->email ?? '' }}</span>
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