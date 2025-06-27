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
                                <select id="cliente_id" name="cliente_id" class="form-select select2" required>
                                    <option value="">Seleccione un cliente</option>
                                    @foreach($clientes as $cliente)
                                        <option value="{{ $cliente->id }}">{{ $cliente->nombre_completo }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div id="cliente_resumen" class="mb-3"></div>
                            <div id="paquetes_container" class="mb-3"></div>
                            <div id="facturas_historial" class="mb-3"></div>
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
                            <div id="inputs_paquetes"></div>
                            <input type="hidden" name="monto_total" id="monto_total" value="0">
                            <input type="hidden" name="monto_local" id="monto_local" value="0">
                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary" id="btn_guardar_factura">
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
<script>
$(document).ready(function() {
    $('#cliente_id').select2({
        placeholder: 'Seleccione un cliente',
        width: '100%'
    });
    let paquetesSeleccionados = [];
    function mostrarSpinner(contenedor) {
        $(contenedor).html('<div class="text-center py-3"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>');
    }
    function limpiarContenedores() {
        $('#cliente_resumen').empty();
        $('#paquetes_container').empty();
        $('#facturas_historial').empty();
        paquetesSeleccionados = [];
        $('#btn_guardar_factura').prop('disabled', true);
    }
    $('#cliente_id').on('change', function() {
        const clienteId = $(this).val();
        limpiarContenedores();
        if (!clienteId) return;
        mostrarSpinner('#cliente_resumen');
        mostrarSpinner('#paquetes_container');
        mostrarSpinner('#facturas_historial');
        $.ajax({
            url: `/api/clientes/${clienteId}`,
            method: 'GET',
            dataType: 'json',
            success: function(resp) {
                if (!resp.success) {
                    $('#cliente_resumen').html('<div class="alert alert-danger">No se pudo cargar la información del cliente.</div>');
                    return;
                }
                // Resumen cliente
                const c = resp.cliente;
                $('#cliente_resumen').html(`
                    <div class="card border-info mb-2"><div class="card-body">
                        <h5 class="card-title mb-2"><i class="fas fa-user text-info me-2"></i>Resumen del Cliente</h5>
                        <p><strong>Nombre:</strong> ${c.nombre_completo ?? '-'}<br>
                        <strong>Dirección:</strong> ${c.direccion ?? '-'}<br>
                        <strong>Teléfono:</strong> ${c.telefono ?? '-'}</p>
                    </div></div>
                `);
                // Paquetes
                if (!resp.paquetes || resp.paquetes.length === 0) {
                    $('#paquetes_container').html('<div class="alert alert-warning"><i class="fas fa-exclamation-triangle me-1"></i> No hay paquetes disponibles para facturar a este cliente.</div>');
                    $('#btn_guardar_factura').prop('disabled', true);
                } else {
                    let tabla = `<table class="table table-hover table-bordered align-middle shadow-sm rounded-3" style="background:#fff;">
                        <thead class="table-primary">
                            <tr>
                                <th></th>
                                <th>Guía</th>
                                <th>Notas</th>
                                <th>Tracking</th>
                                <th>Servicio</th>
                                <th>Peso (lb)</th>
                                <th>Precio Unitario</th>
                                <th>Monto</th>
                            </tr>
                        </thead>
                        <tbody>`;
                    resp.paquetes.forEach(p => {
                        tabla += `<tr>
                            <td><input type="checkbox" class="paquete-checkbox" value="${p.id}"></td>
                            <td>${p.numero_guia ?? '-'}</td>
                            <td>${p.notas ?? '-'}</td>
                            <td>${p.tracking_codigo ?? '-'}</td>
                            <td>${p.servicio ?? '-'}</td>
                            <td>${p.peso_lb ?? '-'}</td>
                            <td>$${parseFloat(p.tarifa_manual ?? p.tarifa ?? 1).toFixed(2)}</td>
                            <td>$${parseFloat(p.monto_calculado).toFixed(2)}</td>
                        </tr>`;
                    });
                    tabla += '</tbody></table>';
                    // Inputs ocultos para paquetes seleccionados
                    tabla += '<div id="inputs_paquetes"></div>';
                    $('#paquetes_container').html(tabla);
                    $('#btn_guardar_factura').prop('disabled', true);
                }
                // Historial
                if (!resp.historial || resp.historial.length === 0) {
                    $('#facturas_historial').html('<div class="alert alert-secondary">Sin historial de facturas.</div>');
                } else {
                    let hist = `<div class="card mb-2"><div class="card-body"><h6 class="fw-semibold">Últimas 5 facturas</h6><table class="table table-sm table-bordered mb-0"><thead class="table-light"><tr><th>#</th><th>Fecha</th><th>Monto</th><th>Estado</th></tr></thead><tbody>`;
                    resp.historial.forEach(f => {
                        hist += `<tr><td>${f.id}</td><td>${f.fecha_factura}</td><td>$${parseFloat(f.monto_total).toFixed(2)}</td><td>${f.estado_pago === 'pagado' ? '<span class="badge bg-success">Pagado</span>' : f.estado_pago === 'parcial' ? '<span class="badge bg-warning text-dark">Parcial</span>' : '<span class="badge bg-danger">Pendiente</span>'}</td></tr>`;
                    });
                    hist += '</tbody></table></div></div>';
                    $('#facturas_historial').html(hist);
                }
            },
            error: function() {
                $('#cliente_resumen').html('<div class="alert alert-danger">Error al cargar los datos del cliente.</div>');
                $('#paquetes_container').html('');
                $('#facturas_historial').html('');
                $('#btn_guardar_factura').prop('disabled', true);
            }
        });
    });
    // Manejo de selección de paquetes
    $(document).on('change', '.paquete-checkbox', function() {
        paquetesSeleccionados = $('.paquete-checkbox:checked').map(function(){ return this.value; }).get();
        // Actualiza inputs ocultos
        let inputs = '';
        paquetesSeleccionados.forEach(id => {
            var fila = $(".paquete-checkbox[value='"+id+"']").closest('tr');
            inputs += `<input type="hidden" name="paquetes[]" value="${id}">`;
            inputs += `<input type="hidden" name="paquete_guia_${id}" value="${fila.find('td').eq(1).text().trim()}">`;
            inputs += `<input type="hidden" name="paquete_descripcion_${id}" value="${fila.find('td').eq(2).text().trim()}">`;
            inputs += `<input type="hidden" name="paquete_tracking_${id}" value="${fila.find('td').eq(3).text().trim()}">`;
            inputs += `<input type="hidden" name="paquete_servicio_${id}" value="${fila.find('td').eq(4).text().trim()}">`;
            inputs += `<input type="hidden" name="paquete_peso_${id}" value="${fila.find('td').eq(5).text().trim()}">`;
            inputs += `<input type="hidden" name="paquete_tarifa_${id}" value="${parseFloat(fila.find('td').eq(6).text().replace('$','')) || 0}">`;
            inputs += `<input type="hidden" name="paquete_valor_${id}" value="${parseFloat(fila.find('td').eq(7).text().replace('$','')) || 0}">`;
        });
        $('#inputs_paquetes').html(inputs);
        // Habilita o deshabilita el botón guardar
        $('#btn_guardar_factura').prop('disabled', paquetesSeleccionados.length === 0);
        actualizarMontosFactura();
    });

    function actualizarMontosFactura() {
        let total = 0;
        $('.paquete-checkbox:checked').each(function() {
            var fila = $(this).closest('tr');
            total += parseFloat(fila.find('td').eq(6).text().replace('$','')) || 0;
        });
        $('#monto_total').val(total.toFixed(2));
        $('#monto_local').val(total.toFixed(2)); // Si tienes lógica de moneda local, cámbiala aquí
    }

    // PDF Preview (mantener tu lógica actual)
    function updatePreview() {
        var form = document.getElementById('factura-form');
        var formData = new FormData(form);
        var cliente = getClienteData();
        formData.append('cliente_nombre', cliente.nombre_completo || '');
        formData.append('cliente_direccion', cliente.direccion || '');
        formData.append('cliente_telefono', cliente.telefono || '');

        // Si no hay paquetes seleccionados, limpiar el PDF y no enviar nada
        if ($('.paquete-checkbox:checked').length === 0) {
            document.getElementById('preview-pdf').src = '';
            return;
        }
        // Ya no agregamos los paquetes manualmente, los inputs ocultos lo hacen

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

    function getClienteData() {
        const clienteId = $('#cliente_id').val();
        let cliente = {};
        // Extraer datos del resumen del cliente si está presente
        const resumen = $('#cliente_resumen').text();
        cliente.nombre_completo = resumen.match(/Nombre:\s*([^\n]+)/) ? resumen.match(/Nombre:\s*([^\n]+)/)[1].trim() : '';
        cliente.direccion = resumen.match(/Dirección:\s*([^\n]+)/) ? resumen.match(/Dirección:\s*([^\n]+)/)[1].trim() : '';
        cliente.telefono = resumen.match(/Teléfono:\s*([^\n]+)/) ? resumen.match(/Teléfono:\s*([^\n]+)/)[1].trim() : '';
        return cliente;
    }

    // Llamar updatePreview cuando se selecciona cliente o paquetes o cambia algún campo relevante
    $('#cliente_id').on('change', function() { setTimeout(updatePreview, 300); });
    $(document).on('change', '.paquete-checkbox', function() { setTimeout(updatePreview, 300); });
    $('#factura-form').on('input change', 'input, select, textarea', function() { setTimeout(updatePreview, 300); });
});
</script>
@endsection
