@extends('layouts.app')

@section('title', 'Inventario - SkylinkOne CRM')
@section('page-title', 'Inventario de Paquetes')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-boxes me-2 text-primary"></i>
                        Inventario de Paquetes
                    </h1>
                    <p class="text-muted mb-0">Gestiona todos los paquetes en el sistema</p>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                        <i class="fas fa-filter me-1"></i>
                        Filtros
                    </button>
                    <a href="{{ route('inventario.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i>
                        Nuevo Paquete
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters Section -->
    <div class="collapse mb-4" id="filtersCollapse">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Buscar</label>
                        <input type="text" class="form-control" id="searchInput" placeholder="Buscar por guía, cliente...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Estado</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos</option>
                            <option value="recibido">Recibido</option>
                            <option value="en_transito">En Tránsito</option>
                            <option value="entregado">Entregado</option>
                            <option value="pendiente">Pendiente</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold">Servicio</label>
                        <select class="form-select" id="serviceFilter">
                            <option value="">Todos</option>
                            <option value="express">Express</option>
                            <option value="estandar">Estándar</option>
                            <option value="economico">Económico</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-semibold">Rango de Fechas</label>
                        <div class="input-group">
                            <input type="date" class="form-control" id="dateFrom">
                            <span class="input-group-text">a</span>
                            <input type="date" class="form-control" id="dateTo">
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-outline-primary w-100" id="clearFilters">
                            <i class="fas fa-times me-1"></i>
                            Limpiar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-boxes text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Total Paquetes</h6>
                            <h4 class="mb-0 fw-bold text-dark">{{ $inventarios->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-clock text-warning fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">En Tránsito</h6>
                            <h4 class="mb-0 fw-bold text-dark">{{ $inventarios->where('estado', 'en_transito')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-success bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-check-circle text-success fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Entregados</h6>
                            <h4 class="mb-0 fw-bold text-dark">{{ $inventarios->where('estado', 'entregado')->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-info bg-opacity-10 rounded-circle p-3">
                                <i class="fas fa-dollar-sign text-info fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title text-muted mb-1">Valor Total</h6>
                            <h4 class="mb-0 fw-bold text-dark">${{ number_format($inventarios->sum('monto_calculado'), 2) }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold text-dark">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Lista de Paquetes
                </h5>
                <div class="d-flex gap-2">
                    <button class="btn btn-outline-secondary btn-sm" id="exportBtn">
                        <i class="fas fa-download me-1"></i>
                        Exportar
                    </button>
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="fas fa-cog me-1"></i>
                            Acciones
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#"><i class="fas fa-print me-2"></i>Imprimir</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-file-excel me-2"></i>Exportar Excel</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-cog me-2"></i>Configuración</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="inventarioTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-user me-1 text-muted"></i>
                                Cliente
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-shipping-fast me-1 text-muted"></i>
                                Servicio
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-weight-hanging me-1 text-muted"></i>
                                Peso
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-cube me-1 text-muted"></i>
                                Volumen
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-barcode me-1 text-muted"></i>
                                Guía
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-info-circle me-1 text-muted"></i>
                                Estado
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-calendar me-1 text-muted"></i>
                                Ingreso
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-dollar-sign me-1 text-muted"></i>
                                Monto
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark text-center">
                                <i class="fas fa-cogs me-1 text-muted"></i>
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventarios as $item)
                            <tr class="align-middle">
                                <td class="px-4 py-3">
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                            <i class="fas fa-user text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $item->cliente->nombre_completo }}</div>
                                            <small class="text-muted">{{ $item->cliente->email ?? 'Sin email' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3">
                                    <span class="badge bg-light text-dark border">
                                        <i class="fas fa-shipping-fast me-1"></i>
                                        {{ $item->servicio->tipo_servicio ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold">{{ number_format($item->peso_lb, 2) }} lb</div>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-semibold">{{ number_format($item->volumen_pie3, 2) }} ft³</div>
                                </td>
                                <td class="px-4 py-3">
                                    <code class="bg-light px-2 py-1 rounded">{{ $item->numero_guia }}</code>
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'recibido' => 'success',
                                            'en_transito' => 'warning',
                                            'entregado' => 'primary',
                                            'pendiente' => 'secondary'
                                        ];
                                        $statusIcons = [
                                            'recibido' => 'check-circle',
                                            'en_transito' => 'truck',
                                            'entregado' => 'box-open',
                                            'pendiente' => 'clock'
                                        ];
                                        $color = $statusColors[$item->estado] ?? 'secondary';
                                        $icon = $statusIcons[$item->estado] ?? 'question-circle';
                                    @endphp
                                    <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                        <i class="fas fa-{{ $icon }} me-1"></i>
                                        {{ ucfirst(str_replace('_', ' ', $item->estado)) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->fecha_ingreso)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($item->fecha_ingreso)->diffForHumans() }}
                                    </small>
                                </td>
                                <td class="px-4 py-3">
                                    <div class="fw-bold text-success">${{ number_format($item->monto_calculado, 2) }}</div>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('inventario.edit', $item->id) }}" 
                                           class="btn btn-outline-primary btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Editar paquete">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('inventario.show', $item->id) }}" 
                                           class="btn btn-outline-info btn-sm" 
                                           data-bs-toggle="tooltip" 
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-outline-danger btn-sm" 
                                                onclick="confirmDelete({{ $item->id }})"
                                                data-bs-toggle="tooltip" 
                                                title="Eliminar paquete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="fas fa-box-open fa-3x mb-3"></i>
                                        <h5>No hay paquetes registrados</h5>
                                        <p>Comienza agregando tu primer paquete al inventario</p>
                                        <a href="{{ route('inventario.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-1"></i>
                                            Registrar Paquete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($inventarios->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $inventarios->links() }}
        </div>
    @endif
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                    Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este paquete? Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash me-1"></i>
                        Eliminar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.table tbody tr {
    transition: background-color 0.2s ease-in-out;
}

.table tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.05) !important;
}

.btn-group .btn {
    border-radius: 0.375rem !important;
    margin: 0 1px;
}

.badge {
    font-size: 0.75rem;
    padding: 0.5em 0.75em;
}

.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.alert {
    border: none;
    border-radius: 0.5rem;
}

.navbar-brand {
    font-weight: 700;
    font-size: 1.5rem;
}

@media (max-width: 768px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin: 1px 0;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const table = document.getElementById('inventarioTable');
    const rows = table.getElementsByTagName('tr');

    searchInput.addEventListener('keyup', function() {
        const searchTerm = this.value.toLowerCase();
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const text = row.textContent.toLowerCase();
            
            if (text.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        }
    });

    // Status filter
    const statusFilter = document.getElementById('statusFilter');
    statusFilter.addEventListener('change', function() {
        const selectedStatus = this.value.toLowerCase();
        
        for (let i = 1; i < rows.length; i++) {
            const row = rows[i];
            const statusCell = row.querySelector('td:nth-child(6)');
            
            if (statusCell) {
                const status = statusCell.textContent.toLowerCase();
                if (!selectedStatus || status.includes(selectedStatus)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        }
    });

    // Clear filters
    document.getElementById('clearFilters').addEventListener('click', function() {
        searchInput.value = '';
        statusFilter.value = '';
        document.getElementById('serviceFilter').value = '';
        document.getElementById('dateFrom').value = '';
        document.getElementById('dateTo').value = '';
        
        // Show all rows
        for (let i = 1; i < rows.length; i++) {
            rows[i].style.display = '';
        }
    });
});

function confirmDelete(id) {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    const form = document.getElementById('deleteForm');
    form.action = `/inventario/${id}`;
    modal.show();
}
</script>
@endsection
