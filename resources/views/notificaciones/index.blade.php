@extends('layouts.app')

@section('title', 'Notificaciones - SkylinkOne CRM')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 fw-bold text-gray-900 mb-0">Notificaciones</h1>
        <div class="d-flex gap-2">
            <button id="toggleViewBtn" class="btn btn-outline-secondary">
                <i class="fas fa-th-large"></i> Cambiar Vista
            </button>
            <a href="{{ route('notificaciones.create') }}" class="btn btn-primary shadow-sm">
                <i class="fas fa-plus me-2"></i>Nueva Notificación
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Vista Tabla -->
    <div id="notificacionesTabla" style="display: block;">
        <div class="card shadow">
            <div class="card-header bg-white border-0">
                <h6 class="m-0 fw-bold text-primary">
                    <i class="fas fa-bell me-2"></i>Notificaciones ({{ $notificaciones->total() }})
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Usuario</th>
                                <th>Estado</th>
                                <th>Título</th>
                                <th>Mensaje</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notificaciones as $notificacion)
                            <tr>
                                <td>
                                    <div class="fw-semibold">{{ $notificacion->usuario->name ?? 'Usuario no encontrado' }}</div>
                                    <small class="text-muted">{{ $notificacion->usuario->email ?? '' }}</small>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-{{ $notificacion->leido ? 'success' : 'warning' }} {{ $notificacion->leido ? '' : 'text-dark' }}">
                                        <i class="fas {{ $notificacion->leido ? 'fa-check' : 'fa-bell' }} me-1"></i>
                                        {{ $notificacion->leido ? 'Leída' : 'No leída' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-bold text-primary">{{ $notificacion->titulo }}</div>
                                </td>
                                <td>
                                    <span class="text-muted small">{{ Str::limit($notificacion->mensaje, 60) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-secondary border">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $notificacion->fecha ? \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y H:i') : 'N/A' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('notificaciones.show', $notificacion) }}" class="btn btn-outline-primary" title="Ver Detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-outline-warning" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('notificaciones.destroy', $notificacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta notificación?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>No hay notificaciones disponibles</p>
                                        <a href="{{ route('notificaciones.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>Crear Notificación
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($notificaciones->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $notificaciones->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Vista Tarjetas -->
    <div id="notificacionesTarjetas" style="display: none;">
        <div class="row g-4">
            @forelse($notificaciones as $notificacion)
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card shadow-sm h-100 border-0 position-relative">
                    <div class="card-body pb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 1.2rem;">
                                    <i class="fas fa-user"></i>
                                </div>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark small mb-1">
                                    {{ $notificacion->usuario->name ?? 'Usuario no encontrado' }}
                                </div>
                                <div class="text-muted small">{{ $notificacion->usuario->email ?? '' }}</div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <span class="badge rounded-pill bg-{{ $notificacion->leido ? 'success' : 'warning' }} me-2 {{ $notificacion->leido ? '' : 'text-dark' }}">
                                <i class="fas {{ $notificacion->leido ? 'fa-check' : 'fa-bell' }} me-1"></i>
                                {{ $notificacion->leido ? 'Leída' : 'No leída' }}
                            </span>
                            <span class="badge bg-light text-secondary border">
                                <i class="fas fa-calendar-alt me-1"></i>
                                {{ $notificacion->fecha ? \Carbon\Carbon::parse($notificacion->fecha)->format('d/m/Y H:i') : 'N/A' }}
                            </span>
                        </div>
                        <h5 class="card-title text-primary mb-2" style="min-height: 2.2em;">{{ $notificacion->titulo }}</h5>
                        <p class="card-text text-muted small" style="min-height: 3.5em;">
                            {{ Str::limit($notificacion->mensaje, 100) }}
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-end gap-2 position-absolute bottom-0 end-0 w-100 pb-3 pe-3">
                        <a href="{{ route('notificaciones.show', $notificacion) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalles">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('notificaciones.edit', $notificacion) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('notificaciones.destroy', $notificacion) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Estás seguro de que quieres eliminar esta notificación?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-bell-slash fa-3x text-muted mb-3"></i>
                <p class="text-muted">No hay notificaciones disponibles</p>
            </div>
            @endforelse
        </div>
        @if($notificaciones->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $notificaciones->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
const tabla = document.getElementById('notificacionesTabla');
const tarjetas = document.getElementById('notificacionesTarjetas');
const btn = document.getElementById('toggleViewBtn');
let vistaTabla = true;
btn.addEventListener('click', function() {
    vistaTabla = !vistaTabla;
    if (vistaTabla) {
        tabla.style.display = 'block';
        tarjetas.style.display = 'none';
        btn.innerHTML = '<i class="fas fa-th-large"></i> Cambiar Vista';
    } else {
        tabla.style.display = 'none';
        tarjetas.style.display = 'block';
        btn.innerHTML = '<i class="fas fa-list"></i> Cambiar Vista';
    }
});
</script>
@endsection 