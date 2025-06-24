@extends('layouts.app')

@section('title', 'Dashboard - SkyLink One CRM')
@section('page-title', 'Dashboard General')

@section('content')
<div class="container-fluid px-4 pt-4">
    <div class="row mb-4 align-items-center">
        <div class="col-12 d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center gap-3">
                <img src="/logo_skylinkone.png" alt="SkyLink One Logo" style="height:40px;">
                <div>
                    <h1 class="h4 mb-0 fw-bold text-primary" style="letter-spacing:1px;">SkyLink One CRM</h1>
                    <span class="text-muted" style="font-size:1rem;">Panel ejecutivo</span>
                </div>
            </div>
            <div>
                <span class="badge bg-light text-dark px-3 py-2" style="font-size:1rem;">
                    {{ now()->format('d M Y') }}
                </span>
            </div>
        </div>
    </div>

    {{-- Módulos principales en recuadros grandes --}}
    <div class="row g-4 mb-4">
        @php
            $modules = [
                [
                    'title' => 'Clientes',
                    'icon' => 'fa-users',
                    'color' => 'primary',
                    'count' => $totalClientes ?? 0,
                    'route' => route('clientes.index')
                ],
                [
                    'title' => 'Usuarios',
                    'icon' => 'fa-user-shield',
                    'color' => 'info',
                    'count' => $totalUsuarios ?? 0,
                    'route' => route('usuarios.index')
                ],
                [
                    'title' => 'Facturación',
                    'icon' => 'fa-file-invoice-dollar',
                    'color' => 'success',
                    'count' => $totalFacturas ?? 0,
                    'route' => route('facturacion.index')
                ],
                [
                    'title' => 'Inventario',
                    'icon' => 'fa-boxes',
                    'color' => 'warning',
                    'count' => $totalPaquetes ?? 0,
                    'route' => route('inventario.index')
                ],
                [
                    'title' => 'Tracking',
                    'icon' => 'fa-route',
                    'color' => 'secondary',
                    'count' => null,
                    'route' => route('tracking.index')
                ],
                [
                    'title' => 'Notificaciones',
                    'icon' => 'fa-bell',
                    'color' => 'danger',
                    'count' => null,
                    'route' => route('notificaciones.index')
                ],
            ];
        @endphp

        @foreach($modules as $mod)
        <div class="col-md-4 col-lg-4 col-xl-4 col-12">
            <a href="{{ $mod['route'] }}" class="text-decoration-none module-card">
                <div class="card border-0 shadow-sm h-100 module-card-animate">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-{{ $mod['color'] }} bg-opacity-10 text-{{ $mod['color'] }} p-4 d-flex align-items-center justify-content-center" style="font-size:2rem;">
                            <i class="fas {{ $mod['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-2 mb-0 text-dark">
                                {{ $mod['count'] !== null ? $mod['count'] : '' }}
                            </div>
                            <div class="fs-5 text-{{ $mod['color'] }} fw-semibold">{{ $mod['title'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold border-bottom-0">
                    <i class="fas fa-chart-bar me-2 text-primary"></i> Estadísticas de Facturación (últimos 6 meses)
                </div>
                <div class="card-body">
                    <canvas id="facturacionChart" height="100"></canvas>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-bold border-bottom-0">
                    <i class="fas fa-boxes me-2 text-warning"></i> Últimos paquetes registrados
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Cliente</th>
                                    <th>Servicio</th>
                                    <th>Guía</th>
                                    <th>Tracking</th>
                                    <th>Fecha ingreso</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosPaquetes ?? [] as $paq)
                                    <tr>
                                        <td>{{ $paq->cliente->nombre_completo ?? '-' }}</td>
                                        <td>{{ $paq->servicio->tipo_servicio ?? '-' }}</td>
                                        <td>{{ $paq->numero_guia }}</td>
                                        <td>{{ $paq->tracking_codigo }}</td>
                                        <td>{{ $paq->fecha_ingreso ? \Carbon\Carbon::parse($paq->fecha_ingreso)->format('d/m/Y') : '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center text-muted">No hay paquetes recientes.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Animación y Chart.js --}}
@push('styles')
<style>
    .module-card-animate {
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background 0.18s;
    }
    .module-card:hover .module-card-animate {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        background: #f8fafc;
        z-index: 2;
    }
    .module-card.selected-module-card .module-card-animate,
    .module-card:active .module-card-animate {
        transform: translateY(-8px) scale(1.03);
        box-shadow: 0 1.5rem 2.5rem rgba(13,110,253,0.18), 0 4px 16px 0 rgba(0,0,0,0.10);
        background: #f1f5fb;
        z-index: 3;
    }
    .module-card {
        display: block;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js CDN (o inclúyelo en tu layout) -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Simulación de datos, reemplaza por tus datos reales si los tienes
    const meses = {!! json_encode(['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun']) !!};
    const montos = {!! json_encode([1200, 1500, 1100, 1800, 2100, 1700]) !!};

    const ctx = document.getElementById('facturacionChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Facturación ($)',
                data: montos,
                backgroundColor: 'rgba(13,110,253,0.2)',
                borderColor: 'rgba(13,110,253,1)',
                borderWidth: 2,
                borderRadius: 8,
                maxBarThickness: 32
            }]
        },
        options: {
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    document.querySelectorAll('.module-card').forEach(function(card) {
        card.addEventListener('click', function(e) {
            e.preventDefault();
            document.querySelectorAll('.module-card').forEach(c => c.classList.remove('selected-module-card'));
            card.classList.add('selected-module-card');
            // Espera la animación y luego navega
            setTimeout(() => {
                window.location.href = card.getAttribute('href');
            }, 250); // 250ms para que se vea la animación
        });
    });
});
</script>
@endpush

@endsection

