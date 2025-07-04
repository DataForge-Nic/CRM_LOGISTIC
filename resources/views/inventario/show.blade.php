@extends('layouts.app')

@section('title', 'Detalle de Paquete - SkylinkOne CRM')
@section('page-title', 'Detalle del Paquete')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-box-open text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Detalle del Paquete</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Información completa del paquete seleccionado</p>
                    </div>
                </div>
                <a href="{{ route('inventario.index') }}" class="btn btn-outline-light fw-semibold shadow-sm px-4">
                    <i class="fas fa-arrow-left me-2"></i> Volver al Inventario
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Información del Paquete
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-user me-1 text-primary"></i>Cliente:</span><br>{{ $paquete->cliente->nombre_completo }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-shipping-fast me-1 text-primary"></i>Servicio:</span><br>{{ $paquete->servicio ? $paquete->servicio->tipo_servicio : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-weight-hanging me-1 text-primary"></i>Peso (lb):</span><br>{{ number_format($paquete->peso_lb, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-cube me-1 text-primary"></i>Volumen (ft³):</span><br>{{ number_format($paquete->volumen_pie3, 2) }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-dollar-sign me-1 text-primary"></i>Tarifa Manual:</span><br>{{ $paquete->tarifa_manual ? '$' . number_format($paquete->tarifa_manual, 2) : 'No aplica' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-calculator me-1 text-primary"></i>Monto Calculado:</span><br><span class="text-success fw-bold">${{ number_format($paquete->monto_calculado, 2) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-calendar me-1 text-primary"></i>Fecha de Ingreso:</span><br>{{ \Carbon\Carbon::parse($paquete->fecha_ingreso)->format('d/m/Y') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-info-circle me-1 text-primary"></i>Estado:</span><br>
                                @php
                                    $statusColors = [
                                        'recibido' => 'success',
                                        'en_transito' => 'warning',
                                        'entregado' => 'primary',
                                        'pendiente' => 'secondary',
                                        'en_oficina' => 'info',
                                    ];
                                    $statusIcons = [
                                        'recibido' => 'check-circle',
                                        'en_transito' => 'truck',
                                        'entregado' => 'box-open',
                                        'pendiente' => 'clock',
                                        'en_oficina' => 'building',
                                    ];
                                    $color = $statusColors[$paquete->estado] ?? 'secondary';
                                    $icon = $statusIcons[$paquete->estado] ?? 'question-circle';
                                @endphp
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ ucfirst(str_replace('_', ' ', $paquete->estado)) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-barcode me-1 text-primary"></i>Número de Guía:</span><br>{{ $paquete->numero_guia ?? 'No asignado' }}</p>
                        </div>
                        <div class="col-md-12">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-sticky-note me-1 text-primary"></i>Notas:</span><br>{{ $paquete->notas ?? 'Sin observaciones' }}</p>
                        </div>
                        <div class="col-md-12">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-file-invoice-dollar me-1 text-primary"></i>Factura Asociada:</span><br>
                                @if($paquete->factura)
                                    <a href="{{ route('facturacion.preview', $paquete->factura->id) }}" class="btn btn-sm btn-outline-info" target="_blank">
                                        <i class="fas fa-file-invoice-dollar me-1"></i>Ver factura #{{ $paquete->factura->id }}
                                    </a>
                                @else
                                    <span class="text-muted">No asignada</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
