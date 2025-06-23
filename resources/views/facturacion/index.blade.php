@extends('layouts.app')

@section('title', 'Facturación - SkylinkOne CRM')
@section('page-title', 'Facturación')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-file-invoice-dollar text-primary me-2"></i>
                        Facturación
                    </h1>
                    <p class="text-muted mb-0">Gestiona todas las facturas del sistema</p>
                </div>
                <a href="{{ route('facturacion.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Nueva Factura
                </a>
            </div>
        </div>
    </div>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Acta</th>
                        <th>Monto</th>
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
                            <td><span class="fw-bold">{{ $factura->monto_total }}</span></td>
                            <td>{{ $factura->moneda }}</td>
                            <td>
                                @if($factura->estado_pago == 'pagado')
                                    <span class="badge bg-success">Pagado</span>
                                @elseif($factura->estado_pago == 'parcial')
                                    <span class="badge bg-warning text-dark">Parcial</span>
                                @else
                                    <span class="badge bg-danger">Pendiente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('facturacion.edit', $factura->id) }}" class="btn btn-sm btn-accent" title="Editar"><i class="fas fa-edit"></i></a>
                                <a href="{{ route('facturacion.preview', $factura->id) }}" class="btn btn-sm btn-info" title="Previsualizar" target="_blank"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('facturacion.pdf', $factura->id) }}" class="btn btn-sm btn-primary" title="Descargar PDF" target="_blank"><i class="fas fa-file-pdf"></i></a>
                                <form action="{{ route('facturacion.destroy', $factura->id) }}" method="POST" style="display:inline;">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro?')" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">No hay facturas registradas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
