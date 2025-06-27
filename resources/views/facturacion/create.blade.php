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
                                <label for="cliente_select" class="form-label fw-semibold">Cliente</label>
                                <select id="cliente_select" class="form-select" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="cliente_id" id="cliente_id_hidden">
                            </div>

                            <div id="clienteResumen" class="mb-3"></div>
                            <div id="clienteHistorial" class="mb-3"></div>

                            <div class="mb-3">
                                <label for="paquetes_select" class="form-label fw-semibold">Selección de paquetes</label>
                                <select id="paquetes_select" name="paquetes[]" class="form-select" multiple style="width:100%"></select>
                                <div class="fw-bold mt-2">Total seleccionado: $<span id="totalSeleccionado">0.00</span></div>
                            </div>
                            <div class="mb-3">
                                <label for="delivery" class="form-label fw-semibold">Costo Delivery (opcional)</label>
                                <input type="number" step="0.01" min="0" name="delivery" id="delivery" class="form-control" value="{{ old('delivery') }}">
                            </div>
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
                                <label for="moneda" class="form-label fw-semibold">Moneda</label>
                                <select name="moneda" class="form-select" required>
                                    <option value="USD" {{ old('moneda') == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="NIO" {{ old('moneda') == 'NIO' ? 'selected' : '' }}>NIO</option>
                                </select>
                                @error('moneda') <div class="text-danger">{{ $message }}</div> @enderror
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
@push('head')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clienteSelect = document.getElementById('cliente_select');
    const clienteHidden = document.getElementById('cliente_id_hidden');
    const resumenDiv = document.getElementById('clienteResumen');
    const historialDiv = document.getElementById('clienteHistorial');
    const paquetesSelect = $('#paquetes_select');
    const totalSpan = document.getElementById('totalSeleccionado');
    const deliveryInput = document.getElementById('delivery');
    const guardarBtn = document.getElementById('guardarFacturaBtn');

    paquetesSelect.select2({
        placeholder: 'Seleccione los paquetes a facturar',
        allowClear: true,
        width: '100%'
    });

    function renderResumen(cliente) {
        if (!cliente) { resumenDiv.innerHTML = ''; return; }
        resumenDiv.innerHTML = `
            <div class="card border-info mb-2">
                <div class="card-body">
                    <h5 class="card-title mb-2"><i class="fas fa-user text-info me-2"></i>Resumen del Cliente</h5>
                    <p><strong>Nombre:</strong> ${cliente.nombre_completo ?? '-'}</p>
                    <p><strong>Dirección:</strong> ${cliente.direccion ?? '-'}</p>
                    <p><strong>Teléfono:</strong> ${cliente.telefono ?? '-'}</p>
                </div>
            </div>
        `;
    }

    function renderHistorial(historial) {
        if (!historial || historial.length === 0) {
            historialDiv.innerHTML = '<div class="alert alert-secondary">Sin historial de facturas.</div>';
            return;
        }
        let rows = historial.map(f => `
            <tr>
                <td>${f.id}</td>
                <td>${f.fecha_factura}</td>
                <td>$${parseFloat(f.monto_total).toFixed(2)}</td>
                <td>
                    ${f.estado_pago === 'pagado' ? '<span class="badge bg-success">Pagado</span>' :
                      f.estado_pago === 'parcial' ? '<span class="badge bg-warning text-dark">Parcial</span>' :
                      '<span class="badge bg-danger">Pendiente</span>'}
                </td>
            </tr>
        `).join('');
        historialDiv.innerHTML = `
            <div class="card mb-2">
                <div class="card-body">
                    <h6 class="fw-semibold">Últimas 5 facturas</h6>
                    <table class="table table-sm table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th><th>Fecha</th><th>Monto</th><th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>${rows}</tbody>
                    </table>
                </div>
            </div>
        `;
    }

    function renderPaquetes(paquetes) {
        paquetesSelect.empty();
        let alerta = document.getElementById('alertaPaquetes');
        if (alerta) alerta.remove();
        if (!paquetes || paquetes.length === 0) {
            paquetesSelect.append(new Option('No hay paquetes disponibles para facturar a este cliente', '', false, false));
            paquetesSelect.trigger('change');
            const alertaDiv = document.createElement('div');
            alertaDiv.id = 'alertaPaquetes';
            alertaDiv.className = 'alert alert-warning mt-2';
            alertaDiv.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> No hay paquetes disponibles para facturar a este cliente.';
            paquetesSelect.parent().append(alertaDiv);
            return;
        }
        paquetes.forEach(inv => {
            let text = `${inv.numero_guia ?? '-'} | ${inv.notas ?? '-'} | ${inv.tracking_codigo ?? '-'} | ${inv.servicio ?? '-'} | ${inv.peso_lb ?? '-'} lb | $${parseFloat(inv.monto_calculado).toFixed(2)}`;
            let option = new Option(text, inv.id, false, false);
            $(option).data({
                monto: inv.monto_calculado,
                guia: inv.numero_guia,
                descripcion: inv.notas,
                tracking: inv.tracking_codigo,
                servicio: inv.servicio,
                tarifa: inv.tarifa_manual ?? inv.monto_calculado,
                peso: inv.peso_lb
            });
            paquetesSelect.append(option);
        });
        paquetesSelect.trigger('change');
    }

    function updateTotal() {
        let total = 0;
        let selected = paquetesSelect.val() || [];
        selected.forEach(function(id) {
            let option = paquetesSelect.find('option[value="'+id+'"]');
            total += parseFloat(option.data('monto') || 0);
        });
        let delivery = parseFloat(deliveryInput.value) || 0;
        totalSpan.textContent = (total + delivery).toFixed(2);
        guardarBtn.disabled = selected.length === 0;
    }

    function cargarDatosCliente(clienteId) {
        if (!clienteId) {
            renderResumen(null);
            renderHistorial([]);
            renderPaquetes([]);
            updateTotal();
            updatePreview();
            return;
        }
        fetch(`/api/facturacion/cliente-detalle/${clienteId}`)
            .then(res => res.json())
            .then(data => {
                renderResumen(data.cliente);
                renderHistorial(data.historial);
                renderPaquetes(data.paquetes);
                updateTotal();
                updatePreview();
            });
    }

    clienteSelect.addEventListener('change', function() {
        const clienteId = this.value;
        clienteHidden.value = clienteId;
        cargarDatosCliente(clienteId);
    });

    paquetesSelect.on('change', function() {
        updateTotal();
        updatePreview();
    });
    if(deliveryInput) {
        deliveryInput.addEventListener('input', function() {
            updateTotal();
            updatePreview();
        });
    }

    document.getElementById('factura-form').addEventListener('submit', function(e) {
        const paquetesSeleccionados = paquetesSelect.val() || [];
        if (paquetesSeleccionados.length === 0) {
            e.preventDefault();
            alert('Debe seleccionar al menos un paquete para crear la factura.');
            return false;
        }
    });

    // PDF Preview (mantener tu lógica actual)
    function updatePreview() {
        var form = document.getElementById('factura-form');
        var formData = new FormData(form);
        var cliente = getClienteData();
        formData.append('cliente_nombre', cliente.nombre_completo || '');
        formData.append('cliente_direccion', cliente.direccion || '');
        formData.append('cliente_telefono', cliente.telefono || '');
        
        // Enviar los paquetes seleccionados
        var paquetesSeleccionados = $('#paquetes_select').val() || [];
        paquetesSeleccionados.forEach(function(id) {
            var option = $('#paquetes_select').find('option[value="'+id+'"]');
            formData.append('paquetes[]', id);
            formData.append('paquete_guia_' + id, option.data('guia') || '');
            formData.append('paquete_descripcion_' + id, option.data('descripcion') || '');
            formData.append('paquete_tracking_' + id, option.data('tracking') || '');
            formData.append('paquete_servicio_' + id, option.data('servicio') || '');
            formData.append('paquete_tarifa_' + id, option.data('tarifa') || 0);
            formData.append('paquete_valor_' + id, option.data('monto') || 0);
            formData.append('paquete_peso_' + id, option.data('peso') || '');
        });
        
        // Enviar delivery
        var deliveryInput = document.getElementById('delivery');
        if (deliveryInput && deliveryInput.value) {
            formData.set('delivery', deliveryInput.value);
        }
        
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

    // Inicialización al cargar
    cargarDatosCliente(clienteSelect.value);
});
</script>
@endsection
