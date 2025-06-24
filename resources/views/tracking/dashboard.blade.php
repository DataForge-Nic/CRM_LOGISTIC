@extends('layouts.app')

@section('title', 'Dashboard de Tracking - SkylinkOne CRM')

@section('content')
<div class="container-fluid">
    <!-- Header del Dashboard -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-900">Dashboard de Tracking</h1>
            <p class="text-muted">Monitoreo y control de seguimientos con temporizadores</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tracking.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Tracking
            </a>
            <a href="{{ route('tracking.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-list me-2"></i>Ver Todos
            </a>
        </div>
    </div>

    <!-- Tarjetas de Estadísticas -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Trackings
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTrackings }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Pendientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trackingsPendientes }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Vencidos
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trackingsVencidos }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Completados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $trackingsCompletados }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Búsqueda Rápida -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-search me-2"></i>Búsqueda Rápida de Tracking
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="input-group">
                                <input type="text" id="codigoTracking" class="form-control" 
                                       placeholder="Ingresa el código de tracking...">
                                <button class="btn btn-primary" type="button" onclick="buscarTracking()">
                                    <i class="fas fa-search me-2"></i>Buscar
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-outline-info w-100" onclick="cargarProximosVencer()">
                                <i class="fas fa-clock me-2"></i>Próximos a Vencer
                            </button>
                        </div>
                    </div>
                    <div id="resultadoBusqueda" class="mt-3"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Próximos a Vencer -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>Próximos a Vencer (7 días)
                    </h6>
                    <span class="badge bg-warning text-dark" id="contadorProximos">0</span>
                </div>
                <div class="card-body">
                    <div id="proximosVencerList" class="row">
                        @forelse($proximosVencer as $tracking)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card border-warning h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title text-warning mb-0">
                                            {{ $tracking->tracking_codigo }}
                                        </h6>
                                        <span class="badge bg-warning text-dark">
                                            {{ \Carbon\Carbon::parse($tracking->recordatorio_fecha)->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="card-text small text-muted mb-2">
                                        <strong>Cliente:</strong> {{ $tracking->cliente->nombre }}
                                    </p>
                                    <p class="card-text small text-muted mb-3">
                                        <strong>Vence:</strong> {{ \Carbon\Carbon::parse($tracking->recordatorio_fecha)->format('d/m/Y H:i') }}
                                    </p>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-sm btn-outline-primary" 
                                                onclick="verTracking({{ $tracking->id }})">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        <button class="btn btn-sm btn-success" 
                                                onclick="marcarCompletado({{ $tracking->id }})">
                                            <i class="fas fa-check"></i> Completar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12 text-center text-muted py-4">
                            <i class="fas fa-check-circle fa-3x mb-3"></i>
                            <p>No hay trackings próximos a vencer</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Temporizadores Activos -->
    <div class="row">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-info">
                        <i class="fas fa-stopwatch me-2"></i>Temporizadores Activos
                    </h6>
                </div>
                <div class="card-body">
                    <div id="temporizadoresActivos" class="row">
                        <!-- Los temporizadores se cargarán dinámicamente -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Detalles del Tracking -->
<div class="modal fade" id="trackingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detalles del Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="modalContent">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
// Variables globales
let temporizadores = {};

// Función para buscar tracking
function buscarTracking() {
    const codigo = document.getElementById('codigoTracking').value;
    if (!codigo) {
        mostrarAlerta('Por favor ingresa un código de tracking', 'warning');
        return;
    }

    fetch(`/tracking/buscar?codigo=${encodeURIComponent(codigo)}`)
        .then(response => response.json())
        .then(data => {
            const resultadoDiv = document.getElementById('resultadoBusqueda');
            
            if (data.success) {
                const tracking = data.tracking;
                resultadoDiv.innerHTML = `
                    <div class="alert alert-success">
                        <div class="row">
                            <div class="col-md-8">
                                <h6><strong>Código:</strong> ${tracking.tracking_codigo}</h6>
                                <p><strong>Cliente:</strong> ${tracking.cliente.nombre}</p>
                                <p><strong>Estado:</strong> <span class="badge bg-${getEstadoColor(tracking.estado)}">${tracking.estado}</span></p>
                                <p><strong>Vence:</strong> ${new Date(tracking.recordatorio_fecha).toLocaleString()}</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-primary btn-sm" onclick="verTracking(${tracking.id})">
                                    <i class="fas fa-eye me-1"></i>Ver Detalles
                                </button>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                resultadoDiv.innerHTML = `
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al buscar el tracking', 'danger');
        });
}

// Función para cargar próximos a vencer
function cargarProximosVencer() {
    fetch('/tracking/proximos-vencer')
        .then(response => response.json())
        .then(data => {
            const contador = document.getElementById('contadorProximos');
            contador.textContent = data.length;
            
            // Actualizar la lista si es necesario
            if (data.length > 0) {
                // Aquí podrías actualizar la lista dinámicamente
                mostrarAlerta(`Se encontraron ${data.length} trackings próximos a vencer`, 'info');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
}

// Función para ver tracking
function verTracking(id) {
    window.location.href = `/tracking/${id}`;
}

// Función para marcar como completado
function marcarCompletado(id) {
    if (confirm('¿Estás seguro de que quieres marcar este tracking como completado?')) {
        fetch(`/tracking/${id}/actualizar-estado`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ estado: 'completado' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('Tracking marcado como completado', 'success');
                setTimeout(() => location.reload(), 1500);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('Error al actualizar el estado', 'danger');
        });
    }
}

// Función para obtener color del estado
function getEstadoColor(estado) {
    switch (estado) {
        case 'pendiente': return 'warning';
        case 'en_proceso': return 'info';
        case 'completado': return 'success';
        case 'vencido': return 'danger';
        default: return 'secondary';
    }
}

// Función para mostrar alertas
function mostrarAlerta(mensaje, tipo) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${tipo} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${mensaje}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.container-fluid').insertBefore(alertDiv, document.querySelector('.container-fluid').firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Función para crear temporizador
function crearTemporizador(trackingId, fechaVencimiento) {
    const ahora = new Date().getTime();
    const vencimiento = new Date(fechaVencimiento).getTime();
    const diferencia = vencimiento - ahora;
    
    if (diferencia <= 0) {
        return null; // Ya venció
    }
    
    const temporizador = setInterval(() => {
        const tiempoRestante = new Date(fechaVencimiento).getTime() - new Date().getTime();
        
        if (tiempoRestante <= 0) {
            clearInterval(temporizador);
            mostrarAlerta(`¡El tracking ${trackingId} ha vencido!`, 'danger');
            delete temporizadores[trackingId];
        } else {
            // Actualizar el display del temporizador si existe
            const displayElement = document.getElementById(`temporizador-${trackingId}`);
            if (displayElement) {
                const dias = Math.floor(tiempoRestante / (1000 * 60 * 60 * 24));
                const horas = Math.floor((tiempoRestante % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutos = Math.floor((tiempoRestante % (1000 * 60 * 60)) / (1000 * 60));
                const segundos = Math.floor((tiempoRestante % (1000 * 60)) / 1000);
                
                displayElement.innerHTML = `
                    <div class="text-center">
                        <div class="h4 text-danger">${dias}d ${horas}h ${minutos}m ${segundos}s</div>
                        <small class="text-muted">Tiempo restante</small>
                    </div>
                `;
            }
        }
    }, 1000);
    
    return temporizador;
}

// Inicializar temporizadores al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Cargar temporizadores para trackings próximos a vencer
    @foreach($proximosVencer as $tracking)
        const temporizador{{ $tracking->id }} = crearTemporizador({{ $tracking->id }}, '{{ $tracking->recordatorio_fecha }}');
        if (temporizador{{ $tracking->id }}) {
            temporizadores[{{ $tracking->id }}] = temporizador{{ $tracking->id }};
        }
    @endforeach
    
    // Actualizar contador de próximos a vencer
    document.getElementById('contadorProximos').textContent = {{ $proximosVencer->count() }};
});

// Limpiar temporizadores al salir de la página
window.addEventListener('beforeunload', function() {
    Object.values(temporizadores).forEach(temporizador => {
        clearInterval(temporizador);
    });
});
</script>
@endsection 