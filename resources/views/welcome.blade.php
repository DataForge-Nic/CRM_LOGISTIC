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

    <!-- Estadísticas personalizadas y filtro de fecha -->
    {{-- Eliminado: tarjetas generales de estadísticas --}}

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
            <!-- Estadísticas por cliente -->
            <div class="card border-0 shadow-sm mb-4 dashboard-stats-card">
                <div class="card-header bg-white fw-bold border-bottom-0 d-flex align-items-center gap-2">
                    <i class="fas fa-user-friends me-2 text-primary"></i>
                    <span>Estadísticas por Cliente (mes actual)</span>
                </div>
                <div class="card-body">
                    <div class="mb-3 d-flex flex-wrap align-items-end gap-4 justify-content-center dashboard-filtros-row">
                        <div class="filtro-item">
                            <label for="servicioSelect" class="form-label fw-semibold mb-1">Tipo de servicio:</label>
                            <select id="servicioSelect" class="form-select filtro-select">
                                <option value="todos">Todos</option>
                                <option value="maritimo">Marítimo</option>
                                <option value="aereo">Aéreo</option>
                            </select>
                        </div>
                        <div class="filtro-item">
                            <label for="clienteAutocomplete" class="form-label fw-semibold mb-1">Buscar cliente:</label>
                            <div class="autocomplete-wrapper position-relative">
                                <input type="text" id="clienteAutocomplete" class="form-control filtro-input" placeholder="Escribe el nombre del cliente..." autocomplete="off">
                                <ul id="autocompleteList" class="list-group position-absolute w-100 shadow-sm" style="z-index:10; display:none; max-height:180px; overflow-y:auto;"></ul>
                            </div>
                        </div>
                        <div class="filtro-item" style="min-width:240px;max-width:320px;">
                            <label for="fechaRango" class="form-label fw-semibold mb-1">Rango de fechas:</label>
                            <input type="text" id="fechaRango" class="form-control filtro-input" placeholder="Selecciona un rango..." autocomplete="off">
                        </div>
                    </div>
                    <div class="row g-4 justify-content-center dashboard-stats-row">
                        <div class="col-md-4 col-12">
                            <div class="stat-card">
                                <div class="stat-icon stat-bg-blue">
                                    <i class="fas fa-box"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-title">Total Paquetes</div>
                                    <div class="stat-value" id="clienteTotalPaquetes">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="stat-card">
                                <div class="stat-icon stat-bg-green">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-title">Dinero Ganado</div>
                                    <div class="stat-value stat-money" id="clienteDinero">-</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-12">
                            <div class="stat-card">
                                <div class="stat-icon stat-bg-cyan">
                                    <i class="fas fa-weight-hanging"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-title">Libras</div>
                                    <div class="stat-value stat-libras" id="clienteLibras">- lb</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="noClienteDataMsg" class="text-center text-muted py-4" style="display:none;">
                        <i class="fas fa-box-open fa-2x mb-2"></i><br>
                        Este cliente no tiene paquetes registrados este mes.
                    </div>
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
            <div class="card border-0 shadow-sm mb-4 dashboard-stats-card pie-card">
                <div class="card-header bg-white fw-bold border-bottom-0 d-flex align-items-center gap-2">
                    <i class="fas fa-chart-pie me-2 text-info"></i>
                    <span>Distribución de Paquetes por Tipo de Servicio (mes actual)</span>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center">
                    <canvas id="graficoServiciosPie" height="210" style="max-width:260px;"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/themes/airbnb.css">

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
.dashboard-stats-row {
    margin-top: 1.2rem;
    display: flex;
    flex-wrap: nowrap;
    justify-content: center;
    align-items: stretch;
    gap: 1.2rem;
}
.stat-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background: #fff;
    border-radius: 1.2rem;
    box-shadow: 0 4px 24px 0 rgba(26,46,117,0.10), 0 1.5px 6px 0 rgba(92,106,196,0.07);
    padding: 1.2rem 0.7rem 1.1rem 0.7rem;
    min-width: 180px;
    max-width: 220px;
    min-height: 150px;
    height: 150px;
    gap: 0.7rem;
    border: 1.5px solid #f0f4fa;
    position: relative;
    overflow: hidden;
    flex: 1 1 0;
    transition: box-shadow 0.2s, transform 0.2s, background 0.18s;
}
.stat-card:hover {
    box-shadow: 0 8px 32px 0 rgba(26,46,117,0.18), 0 2px 8px 0 rgba(92,106,196,0.10);
    background: #f8fafd;
    transform: translateY(-2px) scale(1.02);
}
.stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    box-shadow: 0 2px 8px rgba(26,46,117,0.10);
    flex-shrink: 0;
    margin-bottom: 0.5rem;
}
.stat-bg-blue {
    background: linear-gradient(135deg, #1A2E75 0%, #5C6AC4 100%);
}
.stat-bg-green {
    background: linear-gradient(135deg, #1ecb7b 0%, #1A2E75 100%);
}
.stat-bg-cyan {
    background: linear-gradient(135deg, #00c6fb 0%, #005bea 100%);
}
.stat-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    width: 100%;
}
.stat-title {
    font-size: 0.98rem;
    color: #6c7a92;
    font-weight: 700;
    letter-spacing: 0.5px;
    margin-bottom: 0.2rem;
    text-transform: uppercase;
    text-align: center;
}
.stat-value {
    font-size: 1.35rem;
    font-weight: 800;
    color: #1A2E75;
    letter-spacing: 1px;
    line-height: 1.1;
    text-align: center;
}
.stat-money {
    color: #1ecb7b;
}
.stat-libras {
    color: #00b6ff;
}
@media (max-width: 991px) {
    .dashboard-stats-row { gap: 0.7rem; }
    .stat-card { min-width: 120px; max-width: 100%; min-height: 110px; height: 110px; padding: 0.7rem 0.3rem 0.7rem 0.3rem; }
    .stat-icon { width: 32px; height: 32px; font-size: 1.1rem; margin-bottom: 0.3rem; }
    .stat-title { font-size: 0.85rem; }
    .stat-value { font-size: 1.01rem; }
}
@media (max-width: 767px) {
    .dashboard-stats-row { flex-direction: column; gap: 0.7rem; flex-wrap: wrap; }
    .stat-card { min-width: 100%; max-width: 100%; min-height: 90px; height: 90px; }
}
.dashboard-filtros-row {
    margin-bottom: 0.7rem;
    gap: 1.2rem !important;
    justify-content: flex-start !important;
    align-items: flex-end !important;
}
.filtro-item {
    display: flex;
    flex-direction: column;
    min-width: 170px;
    max-width: 260px;
}
.filtro-select, .filtro-input {
    font-size: 1.01rem;
    border-radius: 0.7rem;
    padding: 0.45rem 1rem;
    border: 1.2px solid #C3C8D4;
    background: #f8fafc;
    color: #1A2E75;
    font-weight: 500;
    box-shadow: none;
    transition: border 0.18s;
    height: 42px;
}
.filtro-select:focus, .filtro-input:focus {
    border-color: #5C6AC4;
    outline: none;
}
.filtro-item label {
    font-size: 0.98rem;
    color: #1A2E75;
    font-weight: 600;
    margin-bottom: 0.25rem;
}
@media (max-width: 991px) {
    .dashboard-filtros-row { gap: 0.7rem !important; }
    .filtro-item { min-width: 120px; }
}
@media (max-width: 767px) {
    .dashboard-filtros-row { flex-direction: column !important; align-items: stretch !important; gap: 0.5rem !important; }
    .filtro-item { min-width: 100%; max-width: 100%; }
}
.dashboard-stats-card.pie-card {
    min-height: 340px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    padding-top: 2.2rem;
    padding-bottom: 2.2rem;
}
@media (max-width: 991px) {
    .dashboard-stats-card.pie-card { min-height: 220px; padding-top: 1.2rem; padding-bottom: 1.2rem; }
}
@media (max-width: 767px) {
    .dashboard-stats-card.pie-card { min-height: 160px; padding-top: 0.7rem; padding-bottom: 0.7rem; }
    }
</style>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
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
const servicioSelect = document.getElementById('servicioSelect');
let selectedClienteId = null;

const idsCliente = {
    total: document.getElementById('clienteTotalPaquetes'),
    dinero: document.getElementById('clienteDinero'),
    libras: document.getElementById('clienteLibras'),
};

function setClienteStats(data, servicio) {
    if (!data) {
        idsCliente.total.textContent = '-';
        idsCliente.dinero.textContent = '-';
        idsCliente.libras.textContent = '-';
        return;
    }
    let total = 0, dinero = 0, libras = 0;
    if (servicio === 'todos') {
        total = (data.paquetes_aereo ?? 0) + (data.paquetes_maritimo ?? 0);
        dinero = (data.ingresos_aereo ?? 0) + (data.ingresos_maritimo ?? 0);
        libras = (data.libras_aereo ?? 0) + (data.libras_maritimo ?? 0);
    } else if (servicio === 'aereo') {
        total = data.paquetes_aereo ?? 0;
        dinero = data.ingresos_aereo ?? 0;
        libras = data.libras_aereo ?? 0;
    } else if (servicio === 'maritimo') {
        total = data.paquetes_maritimo ?? 0;
        dinero = data.ingresos_maritimo ?? 0;
        libras = data.libras_maritimo ?? 0;
    }
    idsCliente.total.textContent = total;
    idsCliente.dinero.textContent = dinero.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
    idsCliente.libras.textContent = libras.toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2});
}

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
            renderClienteStats(cliente.id);
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
        setClienteStats(null, servicioSelect.value);
        return;
    }
    showSuggestions(term);
});

input.addEventListener('focus', function() {
    if (this.value.trim().length > 0) showSuggestions(this.value.trim());
});

document.addEventListener('click', function(e) {
    if (!list.contains(e.target) && e.target !== input) {
        list.style.display = 'none';
    }
});

function renderClienteStats(clienteId) {
    const data = clientesData[clienteId] || null;
    setClienteStats(data, servicioSelect.value);
    document.getElementById('noClienteDataMsg').style.display = data && (
        (servicioSelect.value === 'todos' && ((data.paquetes_aereo ?? 0) + (data.paquetes_maritimo ?? 0) > 0)) ||
        (servicioSelect.value === 'aereo' && (data.paquetes_aereo ?? 0) > 0) ||
        (servicioSelect.value === 'maritimo' && (data.paquetes_maritimo ?? 0) > 0)
    ) ? 'none' : '';
}

servicioSelect.addEventListener('change', function() {
    if (selectedClienteId) renderClienteStats(selectedClienteId);
});

window.addEventListener('DOMContentLoaded', function() {
    if (input.value && clientesList.length > 0) {
        const cliente = clientesList.find(c => c.nombre_completo === input.value);
        if (cliente) {
            selectedClienteId = cliente.id;
            renderClienteStats(cliente.id);
        }
    }
});

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

// --- Flatpickr para rango de fechas ---
let fechaDesde = null;
let fechaHasta = null;
flatpickr('#fechaRango', {
    mode: 'range',
    dateFormat: 'Y-m-d',
    locale: 'es',
    theme: 'airbnb',
    onChange: function(selectedDates, dateStr) {
        if (selectedDates.length === 2) {
            fechaDesde = selectedDates[0].toISOString().slice(0,10);
            fechaHasta = selectedDates[1].toISOString().slice(0,10);
        } else {
            fechaDesde = null;
            fechaHasta = null;
        }
        fetchClienteStats();
    }
});

servicioSelect.addEventListener('change', fetchClienteStats);
input.addEventListener('change', fetchClienteStats);

function setClienteStatsAjax(data) {
    if (!data) {
        idsCliente.total.textContent = '-';
        idsCliente.dinero.textContent = '-';
        idsCliente.libras.textContent = '-';
        return;
    }
    idsCliente.total.textContent = data.total ?? '-';
    idsCliente.dinero.textContent = data.dinero !== undefined ? parseFloat(data.dinero).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
    idsCliente.libras.textContent = data.libras !== undefined ? parseFloat(data.libras).toLocaleString(undefined, {minimumFractionDigits:2, maximumFractionDigits:2}) : '-';
}
function setClienteStatsLoading() {
    idsCliente.total.textContent = '...';
    idsCliente.dinero.textContent = '...';
    idsCliente.libras.textContent = '...';
}
function fetchClienteStats() {
    if (!selectedClienteId) {
        setClienteStatsAjax(null);
        return;
    }
    setClienteStatsLoading();
    let url = '/dashboard/estadisticas-paquetes-cliente';
    const params = [];
    params.push('cliente_id=' + encodeURIComponent(selectedClienteId));
    params.push('tipo_servicio=' + encodeURIComponent(servicioSelect.value));
    if (fechaDesde) params.push('desde=' + encodeURIComponent(fechaDesde));
    if (fechaHasta) params.push('hasta=' + encodeURIComponent(fechaHasta));
    url += '?' + params.join('&');
    fetch(url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' },
        credentials: 'same-origin',
    })
    .then(r => r.ok ? r.json() : Promise.reject())
    .then(setClienteStatsAjax)
    .catch(() => setClienteStatsAjax(null));
}
</script>
@endpush

@endsection

