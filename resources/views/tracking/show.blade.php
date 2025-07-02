@extends('layouts.app')

@section('title', 'Detalle de Tracking - SkylinkOne CRM')
@section('page-title', 'Detalle del Tracking')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-location-arrow text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Detalle del Tracking</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Información completa del tracking seleccionado</p>
                    </div>
                </div>
                <a href="{{ route('tracking.index') }}" class="btn btn-outline-light fw-semibold shadow-sm px-4">
                    <i class="fas fa-arrow-left me-2"></i> Volver a Trackings
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
                        Información del Tracking
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-barcode me-1 text-primary"></i>Código de Tracking:</span><br>{{ $tracking->tracking_codigo }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-user me-1 text-primary"></i>Cliente:</span><br>{{ $tracking->cliente->nombre_completo ?? '-' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-info-circle me-1 text-primary"></i>Estado:</span><br>
                                @php
                                    $statusColors = [
                                        'pendiente' => 'warning',
                                        'en_proceso' => 'info',
                                        'completado' => 'success',
                                        'vencido' => 'danger',
                                    ];
                                    $statusIcons = [
                                        'pendiente' => 'clock',
                                        'en_proceso' => 'spinner',
                                        'completado' => 'check-circle',
                                        'vencido' => 'exclamation-triangle',
                                    ];
                                    $color = $statusColors[$tracking->estado] ?? 'secondary';
                                    $icon = $statusIcons[$tracking->estado] ?? 'question-circle';
                                @endphp
                                <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} border border-{{ $color }}">
                                    <i class="fas fa-{{ $icon }} me-1"></i>
                                    {{ ucfirst($tracking->estado) }}
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-calendar-check me-1 text-primary"></i>Fecha de Estado:</span><br>{{ $tracking->fecha_estado }}</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-bell me-1 text-primary"></i>Recordatorio:</span><br>{{ $tracking->recordatorio_fecha }}</p>
                        </div>
                        <div class="col-md-12">
                            <p class="mb-2"><span class="fw-semibold text-dark"><i class="fas fa-sticky-note me-1 text-primary"></i>Nota:</span><br>{{ $tracking->nota ?? 'Sin observaciones' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 