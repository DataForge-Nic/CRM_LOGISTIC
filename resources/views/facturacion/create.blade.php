@extends('layouts.app')

@section('title', 'Registrar Factura - SkylinkOne CRM')
@section('page-title', '')

@section('content')
<div class="container-fluid px-4 pt-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-white rounded shadow-sm px-4 py-3 mb-4 d-flex align-items-center justify-content-between" style="min-height:70px;">
                <div class="d-flex align-items-center">
                    <a href="{{ route('facturacion.index') }}" class="btn btn-outline-secondary me-3">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                    <div>
                        <h1 class="h3 mb-1 text-dark fw-bold text-start">Registrar Factura</h1>
                        <p class="text-muted mb-0 text-start">Completa los datos para registrar una nueva factura</p>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <a class="nav-link position-relative" href="#" title="Notificaciones"><i class="fas fa-bell fa-lg"></i></a>
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdownHeader" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle fa-lg me-1"></i> Usuario
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdownHeader">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card p-4">
                <div class="row g-0 align-items-stretch">
                    <div class="col-lg-6 p-3 border-end" style="min-width:320px;">
                        <form id="factura-form" action="{{ route('facturacion.store') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label for="cliente_id" class="form-label fw-semibold">Cliente</label>
                                <form id="clienteSelectForm" method="GET" action="{{ route('facturacion.create') }}">
                                    <select name="cliente_id" class="form-select" required onchange="document.getElementById('clienteSelectForm').submit();">
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ request('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                                @error('cliente_id') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            @if(request('cliente_id'))
                            @php
                                $clienteSel = $clientes->where('id', (int)request('cliente_id'))->first();
                            @endphp
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre</label>
                                <input type="text" class="form-control" value="{{ $clienteSel->nombre_completo ?? '' }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dirección</label>
                                <input type="text" class="form-control" value="{{ $clienteSel->direccion ?? '' }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control" value="{{ $clienteSel->telefono ?? '' }}" readonly>
                            </div>
                            @endif
                            @if(request('cliente_id'))
                            <div class="mb-3">
                                <div class="card border-info mb-2">
                                    <div class="card-body">
                                        <h5 class="card-title mb-2"><i class="fas fa-user text-info me-2"></i>Resumen del Cliente</h5>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Nombre:</strong> {{ $clienteSel->nombre_completo ?? '' }}</p>
                                                <p class="mb-1"><strong>Dirección:</strong> {{ $clienteSel->direccion ?? '' }}</p>
                                                <p class="mb-1"><strong>Teléfono:</strong> {{ $clienteSel->telefono ?? '' }}</p>
                                            </div>
                                            <div class="col-md-6">
                                                <p class="mb-1"><strong>Total de facturas:</strong> {{ $clienteSel->facturas->count() }}</p>
                                                <p class="mb-1"><strong>Facturas pendientes/parciales:</strong> <span class="text-{{ $facturasPendientes > 0 ? 'danger' : 'success' }} fw-bold">{{ $facturasPendientes }}</span></p>
                                            </div>
                                        </div>
                                        @if($facturasPendientes > 0)
                                            <div class="alert alert-warning mt-2 mb-0"><i class="fas fa-exclamation-triangle me-1"></i> Este cliente tiene facturas pendientes o parciales.</div>
                                        @endif
                                        <div class="mt-3">
                                            <h6 class="fw-semibold">Últimas 5 facturas</h6>
                                            <table class="table table-sm table-bordered mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Fecha</th>
                                                        <th>Monto</th>
                                                        <th>Estado</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @forelse($historialFacturas as $fact)
                                                        <tr>
                                                            <td>{{ $fact->id }}</td>
                                                            <td>{{ $fact->fecha_factura }}</td>
                                                            <td>${{ number_format($fact->monto_total,2) }}</td>
                                                            <td>
                                                                @if($fact->estado_pago == 'pagado')
                                                                    <span class="badge bg-success">Pagado</span>
                                                                @elseif($fact->estado_pago == 'parcial')
                                                                    <span class="badge bg-warning text-dark">Parcial</span>
                                                                @else
                                                                    <span class="badge bg-danger">Pendiente</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr><td colspan="4" class="text-center text-muted">Sin historial</td></tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Selecciona los paquetes a facturar</label>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <input type="text" id="busquedaPaquete" class="form-control w-50" placeholder="Buscar por guía, tracking, descripción...">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-outline-primary ms-2" id="seleccionarTodosBtn">Seleccionar todos</button>
                                        <button type="button" class="btn btn-sm btn-outline-secondary ms-1" id="deseleccionarTodosBtn">Deseleccionar todos</button>
                                    </div>
                                </div>
                                <div class="mb-2"><span class="fw-bold">Seleccionados: <span id="contadorSeleccionados">0</span></span></div>
                                <table class="table table-bordered align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th></th>
                                            <th>Warehouse</th>
                                            <th>Descripción</th>
                                            <th>Tracking</th>
                                            <th>Servicio</th>
                                            <th>Precio Unitario</th>
                                            <th>Valor</th>
                                            <th>Vista</th>
                                        </tr>
                                    </thead>
                                    <tbody id="paquetesTableBody">
                                        @foreach($inventarios as $inv)
                                        <tr>
                                            <td><input type="checkbox" class="paquete-checkbox" name="paquetes[]" value="{{ $inv->id }}" data-monto="{{ $inv->monto_calculado }}" data-guia="{{ $inv->numero_guia }}" data-descripcion="{{ $inv->notas }}" data-tracking="{{ $inv->tracking_codigo }}" data-servicio="{{ $inv->servicio ? $inv->servicio->tipo_servicio : '' }}" data-tarifa="{{ $inv->tarifa_manual ?? $inv->monto_calculado }}"></td>
                                            <td>{{ $inv->numero_guia ?? '-' }}</td>
                                            <td>{{ $inv->notas ?? '-' }}</td>
                                            <td>{{ $inv->tracking_codigo ?? '-' }}</td>
                                            <td>{{ $inv->servicio ? $inv->servicio->tipo_servicio : '-' }}</td>
                                            <td>${{ number_format($inv->tarifa_manual ?? $inv->monto_calculado,2) }}</td>
                                            <td>${{ number_format($inv->monto_calculado,2) }}</td>
                                            <td><button type="button" class="btn btn-sm btn-info ver-detalle-btn" data-id="{{ $inv->id }}">Ver</button></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="fw-bold mt-2">Total seleccionado: $<span id="totalSeleccionado">0.00</span></div>
                            </div>
                            <!-- Modal para vista previa de producto -->
                            <div class="modal fade" id="detallePaqueteModal" tabindex="-1" aria-labelledby="detallePaqueteLabel" aria-hidden="true">
                              <div class="modal-dialog">
                                <div class="modal-content">
                                  <div class="modal-header">
                                    <h5 class="modal-title" id="detallePaqueteLabel">Detalle del Paquete</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                                  </div>
                                  <div class="modal-body" id="detallePaqueteBody">
                                    <!-- Detalles dinámicos -->
                                  </div>
                                </div>
                              </div>
                            </div>
                            @endif
                            <div class="mb-3">
                                <label for="fecha_factura" class="form-label fw-semibold">Fecha de Factura</label>
                                <input type="date" name="fecha_factura" class="form-control" value="{{ old('fecha_factura', date('Y-m-d')) }}">
                                @error('fecha_factura') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="numero_acta" class="form-label fw-semibold">Número de Acta</label>
                                <input type="text" name="numero_acta" class="form-control" value="{{ old('numero_acta') }}">
                                @error('numero_acta') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="monto_total" class="form-label fw-semibold">Monto Total</label>
                                <input type="number" name="monto_total" step="0.01" class="form-control" value="{{ old('monto_total') }}">
                                @error('monto_total') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="moneda" class="form-label fw-semibold">Moneda</label>
                                <select name="moneda" class="form-select" required>
                                    <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="NIO" {{ old('moneda') == 'NIO' ? 'selected' : '' }}>NIO</option>
                                </select>
                                @error('moneda') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="tasa_cambio" class="form-label fw-semibold">Tasa de Cambio</label>
                                <input type="number" step="0.0001" name="tasa_cambio" class="form-control" value="{{ old('tasa_cambio') }}">
                                @error('tasa_cambio') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="monto_local" class="form-label fw-semibold">Monto en moneda local</label>
                                <input type="number" name="monto_local" step="0.01" class="form-control" value="{{ old('monto_local') }}">
                                @error('monto_local') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="estado_pago" class="form-label fw-semibold">Estado de Pago</label>
                                <select name="estado_pago" class="form-select">
                                    <option value="pendiente" {{ old('estado_pago') == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="parcial" {{ old('estado_pago') == 'parcial' ? 'selected' : '' }}>Parcial</option>
                                    <option value="pagado" {{ old('estado_pago') == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                </select>
                                @error('estado_pago') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="nota" class="form-label fw-semibold">Nota</label>
                                <textarea name="nota" class="form-control" rows="3">{{ old('nota') }}</textarea>
                                @error('nota') <div class="text-danger">{{ $message }}</div> @enderror
                            </div>
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary" id="guardarFacturaBtn">
                                    <i class="fas fa-save me-1"></i> Guardar
                                </button>
                                <a href="{{ route('facturacion.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 p-3" style="min-width:320px;">
                        <h5 class="fw-bold mb-3"><i class="fas fa-eye text-primary me-2"></i>Previsualización</h5>
                        <iframe id="preview-pdf" src="" style="width:100%; min-height:600px; border:none; background:#fff;" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@section('scripts')
<script>
    function getClienteData() {
        var clienteId = document.querySelector('[name=cliente_id]').value;
        var cliente = {};
        @foreach($clientes as $cliente)
            if (clienteId == '{{ $cliente->id }}') {
                cliente = {
                    nombre_completo: @json($cliente->nombre_completo),
                    direccion: @json($cliente->direccion),
                    telefono: @json($cliente->telefono)
                };
            }
        @endforeach
        return cliente;
    }
    function updatePreview() {
        var form = document.getElementById('factura-form');
        var formData = new FormData(form);
        var cliente = getClienteData();
        formData.append('cliente_nombre', cliente.nombre_completo || '');
        formData.append('cliente_direccion', cliente.direccion || '');
        formData.append('cliente_telefono', cliente.telefono || '');
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('facturacion.preview-live') }}', true);
        xhr.responseType = 'blob';
        xhr.onload = function() {
            if (xhr.status === 200) {
                var url = URL.createObjectURL(xhr.response) + '#toolbar=0&navpanes=0&scrollbar=0';
                document.getElementById('preview-pdf').src = url;
            }
        };
        xhr.send(formData);
    }
    let previewTimeout;
    document.getElementById('factura-form').addEventListener('input', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 600);
    });
    document.getElementById('factura-form').addEventListener('change', function() {
        clearTimeout(previewTimeout);
        previewTimeout = setTimeout(updatePreview, 200);
    });
    window.addEventListener('DOMContentLoaded', function() {
        updatePreview();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // AJAX para recargar productos al cambiar cliente
        const clienteSelect = document.querySelector('select[name="cliente_id"]');
        const paquetesTableBody = document.querySelector('#paquetesTableBody');
        const totalSpan = document.getElementById('totalSeleccionado');
        const montoTotalInput = document.querySelector('input[name="monto_total"]');
        const guardarBtn = document.querySelector('#guardarFacturaBtn');

        function updateTotal() {
            let total = 0;
            let checked = 0;
            document.querySelectorAll('.paquete-checkbox').forEach(cb => {
                if (cb.checked) {
                    total += parseFloat(cb.dataset.monto);
                    checked++;
                }
            });
            totalSpan.textContent = total.toFixed(2);
            if(montoTotalInput) montoTotalInput.value = total.toFixed(2);
            if(guardarBtn) guardarBtn.disabled = checked === 0;
        }

        if (clienteSelect) {
            clienteSelect.addEventListener('change', function() {
                const clienteId = this.value;
                if (!clienteId) return;
                fetch(`/facturacion/paquetes-por-cliente/${clienteId}`)
                    .then(res => res.json())
                    .then(data => {
                        paquetesTableBody.innerHTML = '';
                        if (data.length === 0) {
                            paquetesTableBody.innerHTML = '<tr><td colspan="7" class="text-center text-muted">No hay productos disponibles para facturar.</td></tr>';
                            updateTotal();
                            return;
                        }
                        data.forEach(inv => {
                            paquetesTableBody.innerHTML += `
                            <tr>
                                <td><input type="checkbox" class="paquete-checkbox" name="paquetes[]" value="${inv.id}" data-monto="${inv.monto_calculado}" data-guia="${inv.numero_guia}" data-descripcion="${inv.notas}" data-tracking="${inv.tracking_codigo}" data-servicio="${inv.servicio}" data-tarifa="${inv.tarifa_manual ?? inv.monto_calculado}"></td>
                                <td>${inv.numero_guia ?? '-'}</td>
                                <td>${inv.notas ?? '-'}</td>
                                <td>${inv.tracking_codigo ?? '-'}</td>
                                <td>${inv.servicio ?? '-'}</td>
                                <td>$${parseFloat(inv.tarifa_manual ?? inv.monto_calculado).toFixed(2)}</td>
                                <td>$${parseFloat(inv.monto_calculado).toFixed(2)}</td>
                            </tr>`;
                        });
                        document.querySelectorAll('.paquete-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));
                        updateTotal();
                    });
            });
        }
        document.querySelectorAll('.paquete-checkbox').forEach(cb => cb.addEventListener('change', updateTotal));
        updateTotal();
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Selección masiva
        const seleccionarTodosBtn = document.getElementById('seleccionarTodosBtn');
        const deseleccionarTodosBtn = document.getElementById('deseleccionarTodosBtn');
        const contadorSeleccionados = document.getElementById('contadorSeleccionados');
        function updateContadorSeleccionados() {
            const total = document.querySelectorAll('.paquete-checkbox:checked').length;
            if(contadorSeleccionados) contadorSeleccionados.textContent = total;
        }
        if(seleccionarTodosBtn) seleccionarTodosBtn.onclick = function() {
            document.querySelectorAll('.paquete-checkbox').forEach(cb => { cb.checked = true; });
            updateTotal(); updateContadorSeleccionados();
        };
        if(deseleccionarTodosBtn) deseleccionarTodosBtn.onclick = function() {
            document.querySelectorAll('.paquete-checkbox').forEach(cb => { cb.checked = false; });
            updateTotal(); updateContadorSeleccionados();
        };
        document.querySelectorAll('.paquete-checkbox').forEach(cb => cb.addEventListener('change', updateContadorSeleccionados));
        updateContadorSeleccionados();
        // Filtro de búsqueda
        const busquedaPaquete = document.getElementById('busquedaPaquete');
        if(busquedaPaquete) {
            busquedaPaquete.addEventListener('input', function() {
                const val = this.value.toLowerCase();
                document.querySelectorAll('#paquetesTableBody tr').forEach(tr => {
                    const texto = tr.textContent.toLowerCase();
                    tr.style.display = texto.includes(val) ? '' : 'none';
                });
            });
        }
        // Modal de vista previa
        document.querySelectorAll('.ver-detalle-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                const guia = tr.querySelector('[data-guia]')?.dataset.guia || '-';
                const descripcion = tr.querySelector('[data-descripcion]')?.dataset.descripcion || '-';
                const tracking = tr.querySelector('[data-tracking]')?.dataset.tracking || '-';
                const servicio = tr.querySelector('[data-servicio]')?.dataset.servicio || '-';
                const tarifa = tr.querySelector('[data-tarifa]')?.dataset.tarifa || '-';
                const valor = tr.querySelector('[data-monto]')?.dataset.monto || '-';
                document.getElementById('detallePaqueteBody').innerHTML = `
                    <p><strong>Guía:</strong> ${guia}</p>
                    <p><strong>Descripción:</strong> ${descripcion}</p>
                    <p><strong>Tracking:</strong> ${tracking}</p>
                    <p><strong>Servicio:</strong> ${servicio}</p>
                    <p><strong>Tarifa:</strong> $${parseFloat(tarifa).toFixed(2)}</p>
                    <p><strong>Valor:</strong> $${parseFloat(valor).toFixed(2)}</p>
                `;
                var modal = new bootstrap.Modal(document.getElementById('detallePaqueteModal'));
                modal.show();
            });
        });
    });
</script>
@endsection
