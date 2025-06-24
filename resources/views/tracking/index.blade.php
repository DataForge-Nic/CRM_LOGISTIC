@extends('layouts.app')

@section('title', 'Lista de Trackings - SkylinkOne CRM')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-900">Lista de Trackings</h1>
            <p class="text-muted">Gestión completa de seguimientos y temporizadores</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('tracking.dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-chart-line me-2"></i>Dashboard
            </a>
            <a href="{{ route('tracking.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nuevo Tracking
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtros -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter me-2"></i>Filtros
            </h6>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label for="filtroEstado" class="form-label">Estado</label>
                    <select id="filtroEstado" class="form-select">
                        <option value="">Todos los estados</option>
                        <option value="pendiente">Pendiente</option>
                        <option value="en_proceso">En Proceso</option>
                        <option value="completado">Completado</option>
                        <option value="vencido">Vencido</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="filtroCliente" class="form-label">Cliente</label>
                    <select id="filtroCliente" class="form-select">
                        <option value="">Todos los clientes</option>
                        @foreach($trackings->pluck('cliente.nombre')->unique() as $nombreCliente)
                            @if($nombreCliente)
                                <option value="{{ $nombreCliente }}">{{ $nombreCliente }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="filtroFecha" class="form-label">Fecha de Vencimiento</label>
                    <select id="filtroFecha" class="form-select">
                        <option value="">Todas las fechas</option>
                        <option value="hoy">Vence hoy</option>
                        <option value="semana">Vence esta semana</option>
                        <option value="mes">Vence este mes</option>
                        <option value="vencido">Ya venció</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button class="btn btn-outline-primary" onclick="aplicarFiltros()">
                            <i class="fas fa-search me-2"></i>Aplicar Filtros
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Trackings -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-list me-2"></i>Trackings ({{ $trackings->total() }})
            </h6>
            <div class="d-flex gap-2">
                <button class="btn btn-sm btn-outline-info" onclick="exportarTrackings()">
                    <i class="fas fa-download me-1"></i>Exportar
                </button>
                <button class="btn btn-sm btn-outline-warning" onclick="verificarRecordatorios()">
                    <i class="fas fa-sync me-1"></i>Verificar Recordatorios
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Código</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Temporizador</th>
                            <th>Vence</th>
                            <th>Creado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($trackings as $tracking)
                        <tr class="tracking-row" 
                            data-estado="{{ $tracking->estado }}"
                            data-cliente="{{ $tracking->cliente->nombre ?? '' }}"
                            data-fecha="{{ $tracking->recordatorio_fecha }}">
                            <td>
                                <div class="fw-bold text-primary">{{ $tracking->tracking_codigo }}</div>
                                @if($tracking->nota)
                                    <small class="text-muted">{{ Str::limit($tracking->nota, 50) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="fw-medium">{{ $tracking->cliente->nombre_completo ?? 'Cliente no encontrado' }}</div>
                                <small class="text-muted">{{ $tracking->cliente->correo ?? '' }}</small>
                            </td>
                            <td>
                                <span class="badge
                                    @switch($tracking->estado)
                                        @case('pendiente') bg-warning text-dark @break
                                        @case('en_proceso') bg-info text-dark @break
                                        @case('completado') bg-success @break
                                        @case('vencido') bg-danger @break
                                        @case('cancelado') bg-secondary @break
                                        @default bg-secondary
                                    @endswitch
                                ">
                                    {{ ucfirst($tracking->estado) }}
                                </span>
                            </td>
                            <td>
                                <div id="temporizador-{{ $tracking->id }}" class="temporizador-display">
                                    @if($tracking->recordatorio_fecha && $tracking->estado != 'completado')
                                        @php
                                            $vencimiento = \Carbon\Carbon::parse($tracking->recordatorio_fecha);
                                        @endphp
                                        @if($vencimiento->isFuture())
                                            <div class="text-warning">
                                                <i class="fas fa-clock me-1"></i>
                                                <span class="temporizador-text" data-fecha="{{ $tracking->recordatorio_fecha }}">
                                                    {{ $vencimiento->diffForHumans() }}
                                                </span>
                                            </div>
                                        @else
                                            <div class="text-danger">
                                                <i class="fas fa-exclamation-triangle me-1"></i>
                                                Vencido
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    @if($tracking->recordatorio_fecha)
                                        {{ \Carbon\Carbon::parse($tracking->recordatorio_fecha)->format('d/m/Y H:i') }}
                                    @else
                                        <span class="text-muted">No configurado</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="small">
                                    {{ $tracking->creador->name ?? 'Usuario no encontrado' }}
                                </div>
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($tracking->created_at)->format('d/m/Y') }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('tracking.show', $tracking) }}" 
                                       class="btn btn-outline-primary" title="Ver detalles">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('tracking.edit', $tracking) }}" 
                                       class="btn btn-outline-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button type="button" 
                                            class="btn btn-outline-success" 
                                            onclick="cambiarEstado({{ $tracking->id }})"
                                            title="Cambiar estado">
                                        <i class="fas fa-exchange-alt"></i>
                                    </button>
                                    <button type="button" 
                                            class="btn btn-outline-danger" 
                                            onclick="eliminarTracking({{ $tracking->id }})"
                                            title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center py-4">
                                <div class="text-muted">
                                    <i class="fas fa-inbox fa-3x mb-3"></i>
                                    <p>No hay trackings disponibles</p>
                                    <a href="{{ route('tracking.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus me-2"></i>Crear Primer Tracking
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Paginación -->
            @if($trackings->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $trackings->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal para cambiar estado -->
<div class="modal fade" id="estadoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cambiar Estado del Tracking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="estadoForm">
                    <div class="mb-3">
                        <label for="nuevoEstado" class="form-label">Nuevo Estado</label>
                        <select id="nuevoEstado" class="form-select" required>
                            <option value="">Selecciona un estado</option>
                            <option value="pendiente">Pendiente</option>
                            <option value="en_proceso">En Proceso</option>
                            <option value="completado">Completado</option>
                            <option value="cancelado">Cancelado</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="confirmarCambioEstado()">Guardar Cambio</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
let trackingIdActual = null;

// Función para obtener color del estado
function getEstadoColor(estado) {
    switch (estado) {
        case 'pendiente': return 'warning';
        case 'en_proceso': return 'info';
        case 'completado': return 'success';
        case 'vencido': return 'danger';
        case 'cancelado': return 'secondary';
        default: return 'secondary';
    }
}

// Función para aplicar filtros
function aplicarFiltros() {
    const estado = document.getElementById('filtroEstado').value;
    const cliente = document.getElementById('filtroCliente').value;
    const fecha = document.getElementById('filtroFecha').value;
    
    const filas = document.querySelectorAll('.tracking-row');
    
    filas.forEach(fila => {
        let mostrar = true;
        
        // Filtro por estado
        if (estado && fila.dataset.estado !== estado) {
            mostrar = false;
        }
        
        // Filtro por cliente
        if (cliente && fila.dataset.cliente !== cliente) {
            mostrar = false;
        }
        
        // Filtro por fecha
        if (fecha && fila.dataset.fecha) {
            const fechaVencimiento = new Date(fila.dataset.fecha);
            const ahora = new Date();
            
            switch (fecha) {
                case 'hoy':
                    const inicioDia = new Date(ahora.getFullYear(), ahora.getMonth(), ahora.getDate());
                    const finDia = new Date(inicioDia.getTime() + 24 * 60 * 60 * 1000);
                    if (fechaVencimiento < inicioDia || fechaVencimiento >= finDia) {
                        mostrar = false;
                    }
                    break;
                case 'semana':
                    const inicioSemana = new Date(ahora.getTime() - ahora.getDay() * 24 * 60 * 60 * 1000);
                    const finSemana = new Date(inicioSemana.getTime() + 7 * 24 * 60 * 60 * 1000);
                    if (fechaVencimiento < inicioSemana || fechaVencimiento >= finSemana) {
                        mostrar = false;
                    }
                    break;
                case 'mes':
                    const inicioMes = new Date(ahora.getFullYear(), ahora.getMonth(), 1);
                    const finMes = new Date(ahora.getFullYear(), ahora.getMonth() + 1, 1);
                    if (fechaVencimiento < inicioMes || fechaVencimiento >= finMes) {
                        mostrar = false;
                    }
                    break;
                case 'vencido':
                    if (fechaVencimiento > ahora) {
                        mostrar = false;
                    }
                    break;
            }
        }
        
        fila.style.display = mostrar ? '' : 'none';
    });
}

// Función para cambiar estado
function cambiarEstado(trackingId) {
    trackingIdActual = trackingId;
    document.getElementById('nuevoEstado').value = '';
    new bootstrap.Modal(document.getElementById('estadoModal')).show();
}

// Función para confirmar cambio de estado
function confirmarCambioEstado() {
    const nuevoEstado = document.getElementById('nuevoEstado').value;
    
    if (!nuevoEstado) {
        alert('Por favor selecciona un estado');
        return;
    }
    
    fetch(`/tracking/${trackingIdActual}/actualizar-estado`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ estado: nuevoEstado })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error al actualizar el estado');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al actualizar el estado');
    });
}

// Función para eliminar tracking
function eliminarTracking(trackingId) {
    if (confirm('¿Estás seguro de que quieres eliminar este tracking? Esta acción no se puede deshacer.')) {
        fetch(`/tracking/${trackingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (response.ok) {
                location.reload();
            } else {
                alert('Error al eliminar el tracking');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al eliminar el tracking');
        });
    }
}

// Función para exportar trackings
function exportarTrackings() {
    // Aquí podrías implementar la exportación a Excel/CSV
    alert('Función de exportación en desarrollo');
}

// Función para verificar recordatorios
function verificarRecordatorios() {
    fetch('/tracking/verificar-recordatorios')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al verificar recordatorios');
        });
}

// Actualizar temporizadores cada minuto
setInterval(() => {
    document.querySelectorAll('.temporizador-text').forEach(elemento => {
        const fecha = new Date(elemento.dataset.fecha);
        const ahora = new Date();
        const diferencia = fecha - ahora;
        
        if (diferencia > 0) {
            const dias = Math.floor(diferencia / (1000 * 60 * 60 * 24));
            const horas = Math.floor((diferencia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutos = Math.floor((diferencia % (1000 * 60 * 60)) / (1000 * 60));
            
            if (dias > 0) {
                elemento.textContent = `${dias}d ${horas}h ${minutos}m`;
            } else if (horas > 0) {
                elemento.textContent = `${horas}h ${minutos}m`;
            } else {
                elemento.textContent = `${minutos}m`;
            }
        } else {
            elemento.textContent = 'Vencido';
            elemento.parentElement.className = 'text-danger';
        }
    });
}, 60000);

// Inicializar al cargar la página
document.addEventListener('DOMContentLoaded', function() {
    // Configurar event listeners para filtros
    document.getElementById('filtroEstado').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroCliente').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroFecha').addEventListener('change', aplicarFiltros);
});
</script>
@endsection 