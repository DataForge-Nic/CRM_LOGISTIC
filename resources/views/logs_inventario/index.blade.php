@extends('layouts.app')

@section('title', 'Historial de Inventario')
@section('page-title', 'Historial de Cambios en Inventario')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-history text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Historial de Cambios en Inventario</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Auditoría de todas las acciones sobre paquetes</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards de resumen -->
    <div class="row mb-4">
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-history text-primary fs-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Total Cambios</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ $logs->total() }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-plus text-success fs-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Creaciones</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalCreaciones ?? ($logs->where('accion','crear')->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-md-6 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3 me-3">
                        <i class="fas fa-edit text-warning fs-4"></i>
                    </div>
                    <div>
                        <h6 class="card-title text-muted mb-1">Ediciones</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ $totalEdiciones ?? ($logs->where('accion','editar')->count()) }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-body">
            <form method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-3">
                        <label class="form-label fw-semibold"><i class="fas fa-user me-1 text-primary"></i>Agente</label>
                        <input type="text" name="agente" value="{{ request('agente') }}" class="form-control" placeholder="Nombre agente">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold"><i class="fas fa-tasks me-1 text-primary"></i>Acción</label>
                        <select name="accion" class="form-select">
                            <option value="">Todas</option>
                            <option value="crear" @if(request('accion')=='crear') selected @endif>Crear</option>
                            <option value="editar" @if(request('accion')=='editar') selected @endif>Editar</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold"><i class="fas fa-barcode me-1 text-primary"></i>Warehouse</label>
                        <input type="text" name="warehouse" value="{{ request('warehouse') }}" class="form-control" placeholder="Warehouse">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold"><i class="fas fa-calendar-day me-1 text-primary"></i>Fecha desde</label>
                        <input type="date" name="desde" value="{{ request('desde') }}" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label fw-semibold"><i class="fas fa-calendar-day me-1 text-primary"></i>Fecha hasta</label>
                        <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control">
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-12 d-flex justify-content-end gap-2">
                        <button class="btn btn-primary btn-sm" type="submit"><i class="fas fa-search me-1"></i>Filtrar</button>
                        <a href="{{ route('logs_inventario.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-eraser me-1"></i>Limpiar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabla -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0 fw-semibold text-dark">
                <i class="fas fa-list me-2 text-primary"></i>
                Lista de Cambios en Inventario
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table inventario-table table-hover align-middle mb-0" id="logsInventarioTable">
                    <thead class="table-light align-middle">
                        <tr>
                            <th class="text-nowrap align-middle" style="min-width:120px;"><span class="d-inline-flex align-items-center"><i class="fas fa-calendar-alt me-2"></i>Fecha</span></th>
                            <th class="text-nowrap align-middle" style="min-width:110px;"><span class="d-inline-flex align-items-center"><i class="fas fa-user me-2"></i>Agente</span></th>
                            <th class="text-nowrap align-middle" style="min-width:90px;"><span class="d-inline-flex align-items-center"><i class="fas fa-tasks me-2"></i>Acción</span></th>
                            <th class="text-nowrap align-middle" style="min-width:90px;"><span class="d-inline-flex align-items-center"><i class="fas fa-barcode me-2"></i>Warehouse</span></th>
                            <th class="text-nowrap align-middle" style="min-width:140px;"><span class="d-inline-flex align-items-center"><i class="fas fa-info-circle me-2"></i>Detalles</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $log->usuario->nombre ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $log->accion == 'crear' ? 'success' : 'warning' }}">
                                    {{ ucfirst($log->accion) }}
                                </span>
                            </td>
                            <td>{{ $log->inventario->numero_guia ?? '-' }}</td>
                            <td style="min-width:260px;">
                                @if($log->accion == 'crear')
                                    <span class="text-success fw-semibold"><i class="fas fa-plus-circle me-1"></i>Paquete creado</span>
                                    <button type="button" class="btn btn-inv-action btn-inv-view ms-2" data-bs-toggle="modal" data-bs-target="#detallePaqueteModal{{ $log->id }}" title="Ver detalles del paquete" style="background: #1A2E75; color: #fff;">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <!-- Modal Detalle Paquete -->
                                    <div class="modal fade" id="detallePaqueteModal{{ $log->id }}" tabindex="-1" aria-labelledby="detallePaqueteModalLabel{{ $log->id }}" aria-hidden="true">
                                      <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                          <div class="modal-header" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%);">
                                            <h5 class="modal-title text-white" id="detallePaqueteModalLabel{{ $log->id }}"><i class="fas fa-box-open me-2"></i>Detalle del Paquete</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                          </div>
                                          <div class="modal-body">
                                            <ul class="list-group list-group-flush">
                                                <li class="list-group-item"><strong>Guía:</strong> {{ $log->despues['numero_guia'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Estado:</strong> <span class="badge bg-{{ ($log->despues['estado'] ?? '') == 'recibido' ? 'warning' : 'success' }}">{{ ucfirst($log->despues['estado'] ?? '-') }}</span></li>
                                                <li class="list-group-item"><strong>Peso lb:</strong> {{ $log->despues['peso_lb'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Notas:</strong> {{ $log->despues['notas'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Cliente ID:</strong> {{ $log->despues['cliente_id'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Fecha ingreso:</strong> {{ $log->despues['fecha_ingreso'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Tarifa manual:</strong> {{ $log->despues['tarifa_manual'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Monto calculado:</strong> {{ $log->despues['monto_calculado'] ?? '-' }}</li>
                                                <li class="list-group-item"><strong>Tracking código:</strong> {{ $log->despues['tracking_codigo'] ?? '-' }}</li>
                                            </ul>
                                          </div>
                                          <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                          </div>
                                        </div>
                                      </div>
                                    </div>
                                @else
                                    <span class="text-warning fw-semibold"><i class="fas fa-edit me-1"></i>Paquete editado</span>
                                    @php $diffs = cambiosDiferencia($log->antes, $log->despues); @endphp
                                    @if(count($diffs))
                                        <ul class="list-unstyled mt-2 mb-1">
                                            @foreach($diffs as $campo => $val)
                                                <li class="mb-2">
                                                    <span class="fw-semibold text-primary">{{ $campo }}</span>:
                                                    <span class="badge bg-secondary">{{ $val['antes'] }}</span>
                                                    <span class="mx-2 text-dark" style="font-size:1.2em;vertical-align:middle;">→</span>
                                                    <span class="badge bg-success">{{ $val['despues'] }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <span class="text-muted">No hay cambios relevantes.</span>
                                    @endif
                                    <a href="#" class="small text-decoration-underline" onclick="this.nextElementSibling.style.display='block';this.style.display='none';return false;">Ver detalles técnicos</a>
                                    <pre class="bg-light p-2 rounded small mt-2" style="display:none;">Antes:
{{ json_encode($log->antes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}

Después:
{{ json_encode($log->despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="fas fa-box-open fa-2x mb-2"></i>
                                <div>No hay registros de cambios en inventario.</div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-white border-0">
            {{ $logs->links() }}
        </div>
    </div>
</div>

<style>
.inventario-table {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(26,46,117,0.04);
    border-collapse: separate;
    border-spacing: 0;
}
.inventario-table th, .inventario-table td {
    padding: 0.55rem 0.5rem !important;
    font-size: 0.98rem;
    white-space: nowrap;
    vertical-align: middle !important;
}
.inventario-table th {
    font-size: 1.01rem;
    font-weight: 600;
    background: #1A2E75 !important;
    color: #fff !important;
    border-bottom: 3px solid #5C6AC4;
}
.inventario-table th .fa {
    color: #fff !important;
    opacity: 0.92;
}
.inventario-table td {
    vertical-align: middle;
}
.inventario-table thead th span {
    display: flex;
    align-items: center;
    gap: 0.35em;
    justify-content: flex-start;
}
</style>
@endsection

@php
function cambiosDiferencia($antes, $despues) {
    $traducciones = [
        'updated_at' => 'Actualizado el',
        'peso_lb' => 'Peso lb',
        'monto_calculado' => 'Monto calculado',
        'notas' => 'Notas',
        'estado' => 'Estado',
        'numero_guia' => 'Warehouse',
        // Agrega más traducciones si lo deseas
    ];
    $cambios = [];
    foreach ($despues as $key => $valorNuevo) {
        $valorViejo = $antes[$key] ?? null;
        if ($valorViejo != $valorNuevo && !in_array($key, ['created_at','created_by','factura_id','cliente_id','servicio_id','updated_by','id','tracking_codigo'])) {
            $label = $traducciones[$key] ?? ucfirst(str_replace('_',' ',$key));
            // Si es fecha, formatea
            if ($key === 'updated_at' && $valorViejo && $valorNuevo) {
                $valorViejo = \Carbon\Carbon::parse($valorViejo)->format('d/m/Y H:i');
                $valorNuevo = \Carbon\Carbon::parse($valorNuevo)->format('d/m/Y H:i');
            }
            $cambios[$label] = ['antes' => $valorViejo, 'despues' => $valorNuevo];
        }
    }
    return $cambios;
}
@endphp 