@extends('layouts.app')

@section('title', 'Dashboard - SkyLink One CRM')
@section('page-title', 'Dashboard General')

@section('content')
<div class="container-fluid px-4 pt-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:70px; height:70px; box-shadow:0 2px 8px rgba(0,0,0,0.10);">
                        <i class="fas fa-chart-line text-primary" style="font-size:2.7rem;"></i>
                    </div>
                    <div>
                        <h1 class="h2 mb-1 fw-bold text-white" style="letter-spacing:1px;">SkyLink One CRM</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.15rem;">Panel ejecutivo</p>
                    </div>
                </div>
                <div class="text-end fw-semibold" style="font-size:1.15rem; color:#e3e8f0;">
                    <i class="fas fa-calendar-alt me-2"></i>{{ \Carbon\Carbon::now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Módulos principales en recuadros grandes -->
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
                <div class="card border-0 shadow-sm h-100 module-card-animate dashboard-module-card">
                    <div class="card-body d-flex align-items-center gap-3">
                        <div class="rounded-circle bg-{{ $mod['color'] }} bg-opacity-10 text-{{ $mod['color'] }} p-4 d-flex align-items-center justify-content-center" style="font-size:2.3rem; min-width:70px; min-height:70px;">
                            <i class="fas {{ $mod['icon'] }}"></i>
                        </div>
                        <div>
                            <div class="fw-bold fs-1 mb-0 text-dark">
                                {{ $mod['count'] !== null ? $mod['count'] : '' }}
                            </div>
                            <div class="fs-4 text-{{ $mod['color'] }} fw-semibold">{{ $mod['title'] }}</div>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        @endforeach
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Gráfico profesional de paquetes e ingresos por cliente -->
            <div class="card border-0 shadow-sm mb-4 dashboard-stats-card">
                <div class="card-header bg-white fw-bold border-bottom-0 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-bar me-2 text-primary"></i>
                    <span>Paquetes e Ingresos por Cliente (mes actual)</span>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="clienteAutocomplete" class="form-label fw-semibold">Buscar y seleccionar cliente:</label>
                        <div class="autocomplete-wrapper position-relative" style="max-width: 350px;">
                            <input type="text" id="clienteAutocomplete" class="form-control" placeholder="Escribe el nombre del cliente..." autocomplete="off">
                            <ul id="autocompleteList" class="list-group position-absolute w-100 shadow-sm" style="z-index:10; display:none; max-height:180px; overflow-y:auto;"></ul>
                        </div>
                    </div>
                    <div id="noDataMsg" class="text-center text-muted py-4" style="display:none;">
                        <i class="fas fa-info-circle fa-2x mb-2"></i><br>
                        No hay datos de paquetes ni ingresos para este cliente en el mes actual.
                    </div>
                    <div id="noClienteDataMsg" class="text-center text-muted py-4" style="display:none;">
                        <i class="fas fa-box-open fa-2x mb-2"></i><br>
                        Este cliente no tiene paquetes registrados este mes.
                    </div>
                    <canvas id="graficoCliente" height="170"></canvas>
                </div>
            </div>
            <div class="card border-0 shadow-sm mb-4 dashboard-stats-card">
                <div class="card-header bg-white fw-bold border-bottom-0 d-flex align-items-center gap-2">
                    <i class="fas fa-boxes me-2 text-warning"></i> <span>Últimos paquetes registrados</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table inventario-table table-hover mb-0">
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
        <div class="col-lg-4">
            <!-- Gráfico de pastel de servicios -->
            <div class="card border-0 shadow-sm mb-4 dashboard-stats-card">
                <div class="card-header bg-white fw-bold border-bottom-0 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-pie me-2 text-info"></i>
                    <span>Distribución de Paquetes por Tipo de Servicio (mes actual)</span>
                </div>
                <div class="card-body">
                    <canvas id="graficoServiciosPie" height="210"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
.dashboard-module-card {
    border-radius: 18px;
    background: #fff;
    box-shadow: 0 2px 12px 0 rgba(0,0,0,0.07);
    transition: transform 0.2s, box-shadow 0.2s, background 0.18s;
}
.module-card:hover .dashboard-module-card {
    transform: translateY(-4px) scale(1.03);
    box-shadow: 0 1.5rem 2.5rem rgba(13,110,253,0.13), 0 4px 16px 0 rgba(0,0,0,0.10);
    background: #f8fafc;
    z-index: 2;
}
.dashboard-stats-card {
    border-radius: 18px;
    box-shadow: 0 2px 8px rgba(26,46,117,0.04);
    background: #fff;
    padding: 1.5rem 1.2rem;
    margin-bottom: 1.2rem;
}
.inventario-table {
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(26,46,117,0.04);
    border-collapse: separate;
    border-spacing: 0;
}
.inventario-table thead th {
    background: #1A2E75 !important;
    color: #fff !important;
    font-weight: 600;
    letter-spacing: 0.5px;
    border: none !important;
    padding: 12px 14px !important;
    font-size: 1.05rem;
    vertical-align: middle;
    white-space: nowrap;
}
.inventario-table thead th:first-child {
    border-top-left-radius: 16px;
}
.inventario-table thead th:last-child {
    border-top-right-radius: 16px;
}
.inventario-table tbody tr {
    background: #fff;
    transition: background 0.2s;
    border-bottom: 1.5px solid #e3e6f0;
}
.inventario-table tbody td {
    border: none !important;
    padding: 10px 14px !important;
    vertical-align: middle !important;
    font-size: 1.01rem;
}
.inventario-table tbody tr:hover {
    background: #F0F4FF !important;
}
@media (max-width: 991px) {
    .dashboard-module-card { font-size: 0.95rem; }
    .dashboard-stats-card { padding: 1rem 0.7rem; }
}
@media (max-width: 767px) {
    .dashboard-module-card { font-size: 0.9rem; }
    .dashboard-stats-card { padding: 0.7rem 0.3rem; }
    .inventario-table thead th, .inventario-table tbody td { padding: 8px 6px !important; font-size: 0.97rem; }
}
.autocomplete-wrapper { position: relative; }
#clienteAutocomplete {
    border-radius: 0.75rem;
    border: 1.5px solid #1A2E75;
    font-size: 1.08rem;
    padding: 0.5rem 1.2rem;
    background: #f8fafc;
    color: #1A2E75;
}
#clienteAutocomplete:focus {
    border-color: #5C6AC4;
    box-shadow: 0 0 0 0.15rem rgba(92,106,196,0.10);
}
#autocompleteList {
    border-radius: 0.75rem;
    background: #fff;
    margin-top: 2px;
    border: 1.5px solid #e3e8f0;
    font-size: 1.05rem;
    box-shadow: 0 2px 8px rgba(26,46,117,0.07);
}
#autocompleteList li {
    cursor: pointer;
    padding: 0.6rem 1rem;
    border-bottom: 1px solid #f0f0f0;
    transition: background 0.13s;
}
#autocompleteList li:last-child { border-bottom: none; }
#autocompleteList li:hover, #autocompleteList li.active {
    background: #F0F4FF;
    color: #1A2E75;
    font-weight: 600;
}
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script>
console.log('Script ejecutado');
const clientesData = @json($clientesData ?? []);
const clientesList = @json($clientes ?? []);
const serviciosPieData = @json($serviciosPieData ?? []);
console.log('clientesData:', clientesData);

// Paleta Skylink
const skylinkPalette = [
    '#1A2E75', '#5C6AC4', '#3B5998', '#6A82FB', '#A1C4FD', '#27408B', '#4F8EF7', '#B2CFFF', '#3A7CA5', '#7B9ACC'
];

// --- Gráfico de barras (clientes) ---
const input = document.getElementById('clienteAutocomplete');
const list = document.getElementById('autocompleteList');
let selectedClienteId = null;

function showSuggestions(term) {
    list.innerHTML = '';
    const filtered = clientesList.filter(c => c.nombre_completo.toLowerCase().includes(term.toLowerCase()));
    if (filtered.length === 0) {
        list.style.display = 'none';
        return;
    }
    filtered.forEach(cliente => {
        const li = document.createElement('li');
        li.textContent = cliente.nombre_completo;
        li.onclick = () => {
            input.value = cliente.nombre_completo;
            selectedClienteId = cliente.id;
            list.style.display = 'none';
            renderChart(cliente.id);
        };
        list.appendChild(li);
    });
    list.style.display = 'block';
}

input.addEventListener('input', function() {
    const term = this.value.trim();
    if (term.length === 0) {
        list.style.display = 'none';
        selectedClienteId = null;
        document.getElementById('graficoCliente').style.display = 'none';
        document.getElementById('noDataMsg').style.display = '';
        return;
    }
    showSuggestions(term);
});

input.addEventListener('focus', function() {
    if (this.value.trim().length > 0) showSuggestions(this.value.trim());
});

document.addEventListener('click', function(e) {
    if (!input.contains(e.target) && !list.contains(e.target)) {
        list.style.display = 'none';
    }
});

const ctxCliente = document.getElementById('graficoCliente').getContext('2d');
let chartCliente;
function renderChart(clienteId) {
    const data = clientesData[clienteId] || { paquetes: 0, ingresos: 0 };
    const isEmpty = (data.paquetes === 0 && data.ingresos === 0);
    document.getElementById('noDataMsg').style.display = 'none';
    document.getElementById('noClienteDataMsg').style.display = isEmpty ? '' : 'none';
    document.getElementById('graficoCliente').style.display = isEmpty ? 'none' : '';
    if (isEmpty) {
        if (chartCliente) chartCliente.destroy();
        return;
    }
    if (chartCliente) chartCliente.destroy();
    chartCliente = new Chart(ctxCliente, {
        type: 'bar',
        data: {
            labels: ['Paquetes', 'Ingresos'],
            datasets: [{
                label: '',
                data: [data.paquetes, data.ingresos],
                backgroundColor: [skylinkPalette[0], skylinkPalette[1]],
                borderRadius: 18,
                borderSkipped: false,
                maxBarThickness: 80,
                hoverBackgroundColor: [skylinkPalette[2], skylinkPalette[3]]
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#1A2E75',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#5C6AC4',
                    borderWidth: 1.5,
                    padding: 12,
                    callbacks: {
                        label: function(context) {
                            if (context.dataIndex === 1) {
                                return 'Ingresos: $' + context.parsed.y.toLocaleString();
                            }
                            return 'Paquetes: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#1A2E75', font: { weight: 'bold', size: 15 } }
                },
                y: {
                    beginAtZero: true,
                    grid: { color: '#e3e8f0' },
                    ticks: {
                        color: '#5C6AC4',
                        font: { weight: 'bold', size: 13 },
                        callback: function(value, index, values) {
                            return index === 1 ? '$' + value : value;
                        }
                    }
                }
            }
        }
    });
}
// Inicializar con el primer cliente si existe
if (clientesList.length > 0) {
    input.value = clientesList[0].nombre_completo;
    selectedClienteId = clientesList[0].id;
    renderChart(selectedClienteId);
}

// --- Gráfico de pastel (servicios) ---
const ctxPie = document.getElementById('graficoServiciosPie').getContext('2d');
const pieLabels = Object.keys(serviciosPieData);
const pieData = Object.values(serviciosPieData);
const pieColors = skylinkPalette.slice(0, pieLabels.length);
let chartPie = new Chart(ctxPie, {
    type: 'doughnut',
    data: {
        labels: pieLabels,
        datasets: [{
            data: pieData,
            backgroundColor: pieColors,
            borderColor: '#fff',
            borderWidth: 2,
            hoverOffset: 10
        }]
    },
    options: {
        cutout: '65%',
        plugins: {
            legend: {
                display: true,
                position: 'bottom',
                labels: {
                    color: '#1A2E75',
                    font: { size: 15, weight: 'bold' },
                    padding: 18
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const value = context.parsed;
                        const percent = total ? ((value / total) * 100).toFixed(1) : 0;
                        return `${context.label}: ${value} paquetes (${percent}%)`;
                    }
                },
                backgroundColor: '#5C6AC4',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#1A2E75',
                borderWidth: 1.5,
                padding: 12
            }
        }
    }
});
</script>
@endpush

@endsection

