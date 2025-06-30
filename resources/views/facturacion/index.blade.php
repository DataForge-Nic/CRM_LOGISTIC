@extends('layouts.app')

@section('title', 'Facturación - SkylinkOne CRM')
@section('page-title', 'Facturación')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-file-invoice-dollar text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Facturación</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Gestiona todas las facturas del sistema</p>
                    </div>
                </div>
                <a href="{{ route('facturacion.create') }}" class="btn btn-lg fw-semibold shadow-sm px-4" style="background:#1A2E75; color:#fff;">
                    <i class="fas fa-plus me-2"></i> Nueva Factura
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card p-3">
        <form method="GET" class="row g-2 mb-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label">Cliente</label>
                <input type="text" name="cliente" value="{{ request('cliente') }}" class="form-control" placeholder="Buscar cliente...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Fecha</label>
                <input type="date" name="fecha" value="{{ request('fecha') }}" class="form-control">
            </div>
            <div class="col-md-2">
                <label class="form-label">Acta</label>
                <input type="text" name="acta" value="{{ request('acta') }}" class="form-control" placeholder="N° de acta">
            </div>
            <div class="col-md-2">
                <label class="form-label">Estado</label>
                <select name="estado" class="form-select">
                    <option value="">Todos</option>
                    <option value="pagado" {{ request('estado')=='pagado'?'selected':'' }}>Pagado</option>
                    <option value="parcial" {{ request('estado')=='parcial'?'selected':'' }}>Parcial</option>
                    <option value="pendiente" {{ request('estado')=='pendiente'?'selected':'' }}>Pendiente</option>
                </select>
            </div>
            <div class="col-md-3 filter-btn-group">
                <button class="btn-filter" type="submit"><i class="fas fa-search"></i> Filtrar</button>
                <a href="{{ route('facturacion.index') }}" class="btn-clear"><i class="fas fa-eraser"></i> Limpiar filtros</a>
            </div>
        </form>
        <div class="table-responsive">
            <style>
                .fact-table thead th {
                    background: #1A2E75 !important;
                    color: #fff !important;
                    border-radius: 0 !important;
                    border-bottom: 3px solid #5C6AC4;
                    font-weight: 600;
                    letter-spacing: 0.5px;
                }
                .fact-table thead tr {
                    border-radius: 0 !important;
                }
                .fact-table {
                    border-radius: 12px;
                    overflow: hidden;
                    box-shadow: 0 2px 8px rgba(26,46,117,0.04);
                }
                .fact-table tbody tr {
                    background: #fff;
                    transition: background 0.2s;
                }
                .fact-table tbody tr:hover {
                    background: #F5F7FA !important;
                }
                .btn-fact {
                    border-radius: 8px !important;
                    min-width: 38px;
                    min-height: 38px;
                    padding: 0 12px;
                    font-size: 1.1rem;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    border: none;
                    box-shadow: none;
                    transition: background 0.15s;
                }
                .btn-fact-view {
                    background: #1A2E75;
                    color: #fff;
                }
                .btn-fact-pdf {
                    background: #5C6AC4;
                    color: #fff;
                }
                .btn-fact-delete {
                    background: #BF1E2E;
                    color: #fff;
                }
                .btn-fact:hover, .btn-fact:focus {
                    opacity: 0.92;
                    color: #fff;
                }
                .btn-filter {
                    background: #1A2E75;
                    color: #fff;
                    border-radius: 8px;
                    border: none;
                    min-width: 120px;
                    min-height: 42px;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    font-size: 1rem;
                    box-shadow: none;
                    transition: background 0.15s;
                }
                .btn-filter:hover, .btn-filter:focus {
                    background: #223a7a;
                    color: #fff;
                }
                .btn-clear {
                    background: #F5F7FA;
                    color: #1A2E75;
                    border: 1.5px solid #1A2E75;
                    border-radius: 8px;
                    min-width: 140px;
                    min-height: 42px;
                    font-weight: 600;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 8px;
                    font-size: 1rem;
                    box-shadow: none;
                    transition: background 0.15s;
                    text-decoration: none !important;
                }
                .btn-clear:hover, .btn-clear:focus {
                    background: #e9ecef;
                    color: #1A2E75;
                    border-color: #223a7a;
                }
                .filter-btn-group {
                    display: flex;
                    gap: 10px;
                    align-items: center;
                    justify-content: flex-end;
                }
            </style>
            <table class="table fact-table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Acta</th>
                        <th>Monto Total</th>
                        <th>Moneda</th>
                        <th>Estado</th>
                        <th>Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($facturas as $factura)
                        <tr>
                            <td>{{ $factura->id }}</td>
                            <td>{{ $factura->cliente->nombre_completo ?? 'N/D' }}</td>
                            <td>{{ $factura->fecha_factura }}</td>
                            <td>{{ $factura->numero_acta }}</td>
                            <td><span class="fw-bold">${{ number_format($factura->monto_total, 2) }}</span></td>
                            <td>{{ $factura->moneda }}</td>
                            <td>
                                <form method="POST" action="{{ route('facturacion.cambiar-estado', $factura->id) }}" class="d-inline">
                                    @csrf
                                    <select name="estado_pago" class="form-select form-select-sm d-inline w-auto" onchange="this.form.submit()" @if($factura->estado_pago=='pagado') disabled @endif>
                                        <option value="pendiente" {{ $factura->estado_pago=='pendiente'?'selected':'' }}>Pendiente</option>
                                        <option value="parcial" {{ $factura->estado_pago=='parcial'?'selected':'' }}>Parcial</option>
                                        <option value="pagado" {{ $factura->estado_pago=='pagado'?'selected':'' }}>Pagado</option>
                                    </select>
                                </form>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('facturacion.preview', $factura->id) }}" class="btn-fact btn-fact-view" title="Previsualizar" target="_blank"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('facturacion.pdf', $factura->id) }}" class="btn-fact btn-fact-pdf" title="Descargar PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <form action="{{ route('facturacion.destroy', $factura->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn-fact btn-fact-delete" onclick="return confirm('¿Estás seguro?')" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No hay facturas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($facturas->hasPages())
            <div class="d-flex justify-content-center">
                {{ $facturas->links('vendor.pagination.custom') }}
            </div>
        @endif
    </div>
</div>

<!-- Estilos de animación y paginación igual a inventario -->
<style>
.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}
.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
.pagination {
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 8px;
    margin: 32px 0 16px 0;
    padding: 0;
    list-style: none;
}
.pagination li {
    display: inline-block;
}
.pagination a, .pagination span {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 38px;
    min-height: 38px;
    padding: 0 14px;
    border-radius: 8px;
    border: 1.5px solid #1A2E75;
    background: #fff;
    color: #1A2E75;
    font-weight: 600;
    font-size: 1.08rem;
    text-decoration: none !important;
    transition: background 0.15s, color 0.15s;
    margin: 0 2px;
}
.pagination .active span, .pagination a.active {
    background: #1A2E75;
    color: #fff;
    border-color: #1A2E75;
    cursor: default;
}
.pagination a:hover, .pagination a:focus {
    background: #5C6AC4;
    color: #fff;
    border-color: #5C6AC4;
}
.pagination .disabled span, .pagination .disabled a {
    color: #b0b0b0;
    background: #f5f7fa;
    border-color: #e3e6f0;
    cursor: not-allowed;
}
.pagination .page-arrow {
    font-size: 1.3rem;
    padding: 0 10px;
    min-width: 38px;
    min-height: 38px;
    border-radius: 8px;
    border: 1.5px solid #1A2E75;
    background: #fff;
    color: #1A2E75;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.15s, color 0.15s;
}
.pagination .page-arrow:hover, .pagination .page-arrow:focus {
    background: #5C6AC4;
    color: #fff;
    border-color: #5C6AC4;
}
</style>
@endsection
