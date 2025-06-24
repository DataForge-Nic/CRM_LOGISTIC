@extends('layouts.app')

@section('title', 'Historial de Inventario')
@section('page-title', 'Historial de Cambios en Inventario')

@section('content')
<div class="container-fluid px-4 pt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <h1 class="h4 mb-0 fw-bold text-primary">
                <i class="fas fa-history me-2"></i>Historial de Cambios en Inventario
            </h1>
        </div>
    </div>
    <div class="row mb-3">
        <form method="GET" class="col-12 col-md-10 mx-auto d-flex flex-wrap gap-2 align-items-end bg-white rounded shadow-sm p-3" style="border:1px solid #e3e6f0;">
            <div class="d-flex flex-column me-2">
                <label class="form-label mb-1 fw-semibold"><i class="fas fa-user me-1 text-primary"></i>Agente</label>
                <input type="text" name="agente" value="{{ request('agente') }}" class="form-control form-control-sm" placeholder="Nombre agente">
            </div>
            <div class="d-flex flex-column me-2">
                <label class="form-label mb-1 fw-semibold"><i class="fas fa-tasks me-1 text-primary"></i>Acción</label>
                <select name="accion" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="crear" @if(request('accion')=='crear') selected @endif>Crear</option>
                    <option value="editar" @if(request('accion')=='editar') selected @endif>Editar</option>
                </select>
            </div>
            <div class="d-flex flex-column me-2">
                <label class="form-label mb-1 fw-semibold"><i class="fas fa-barcode me-1 text-primary"></i>Warehouse</label>
                <input type="text" name="warehouse" value="{{ request('warehouse') }}" class="form-control form-control-sm" placeholder="Warehouse">
            </div>
            <div class="d-flex flex-column me-2">
                <label class="form-label mb-1 fw-semibold"><i class="fas fa-calendar-day me-1 text-primary"></i>Fecha desde</label>
                <input type="date" name="desde" value="{{ request('desde') }}" class="form-control form-control-sm">
            </div>
            <div class="d-flex flex-column me-2">
                <label class="form-label mb-1 fw-semibold"><i class="fas fa-calendar-day me-1 text-primary"></i>Fecha hasta</label>
                <input type="date" name="hasta" value="{{ request('hasta') }}" class="form-control form-control-sm">
            </div>
            <div class="d-flex flex-column justify-content-end">
                <button class="btn btn-primary btn-sm mb-1"><i class="fas fa-search me-1"></i>Filtrar</button>
                <a href="{{ route('logs_inventario.index') }}" class="btn btn-outline-secondary btn-sm"><i class="fas fa-eraser me-1"></i>Limpiar</a>
            </div>
        </form>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Fecha</th>
                            <th>Agente</th>
                            <th>Acción</th>
                            <th>Warehouse</th>
                            <th>Detalles</th>
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
                            <td>
                                @if($log->accion == 'crear')
                                    <span class="text-success">Paquete creado</span>
                                    <details>
                                        <summary class="small">Ver datos</summary>
                                        <pre class="bg-light p-2 rounded small">{{ json_encode($log->despues, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                    </details>
                                @else
                                    <span class="text-warning">Paquete editado</span>
                                    <details>
                                        <summary class="small">Ver cambios</summary>
                                        <div class="row">
                                            <div class="col-12">
                                                @php $diffs = cambiosDiferencia($log->antes, $log->despues); @endphp
                                                @if(count($diffs))
                                                    <ul class="list-unstyled mb-0">
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
                                            </div>
                                        </div>
                                    </details>
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