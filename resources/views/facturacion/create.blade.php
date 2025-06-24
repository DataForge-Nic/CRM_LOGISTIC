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
                                <label class="form-label fw-semibold">Selecciona los paquetes a facturar</label>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($inventarios as $inv)
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="paquete-checkbox" name="paquetes[]" value="{{ $inv->id }}" data-monto="{{ $inv->monto_calculado }}" data-guia="{{ $inv->numero_guia }}" data-descripcion="{{ $inv->notas }}" data-tracking="{{ $inv->tracking_codigo }}" data-servicio="{{ $inv->servicio ? $inv->servicio->tipo_servicio : '' }}" data-tarifa="{{ $inv->tarifa_manual ?? $inv->monto_calculado }}">
                                                <input type="hidden" name="paquete_guia_{{ $inv->id }}" value="{{ $inv->numero_guia }}">
                                                <input type="hidden" name="paquete_descripcion_{{ $inv->id }}" value="{{ $inv->notas }}">
                                                <input type="hidden" name="paquete_tracking_{{ $inv->id }}" value="{{ $inv->tracking_codigo }}">
                                                <input type="hidden" name="paquete_servicio_{{ $inv->id }}" value="{{ $inv->servicio ? $inv->servicio->tipo_servicio : '' }}">
                                                <input type="hidden" name="paquete_tarifa_{{ $inv->id }}" value="{{ $inv->tarifa_manual ?? $inv->monto_calculado }}">
                                                <input type="hidden" name="paquete_valor_{{ $inv->id }}" value="{{ $inv->monto_calculado }}">
                                            </td>
                                            <td>{{ $inv->numero_guia ?? '-' }}</td>
                                            <td>{{ $inv->notas ?? '-' }}</td>
                                            <td>{{ $inv->tracking_codigo ?? '-' }}</td>
                                            <td>
                                                @if($inv->servicio)
                                                    {{ Str::contains(strtolower($inv->servicio->tipo_servicio), 'mar') ? 'Mar' : 'Air' }}
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>${{ number_format($inv->tarifa_manual ?? $inv->monto_calculado,2) }}</td>
                                            <td>${{ number_format($inv->monto_calculado,2) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="fw-bold mt-2">Total seleccionado: $<span id="totalSeleccionado">0.00</span></div>
                            </div>
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const checkboxes = document.querySelectorAll('.paquete-checkbox');
                                const totalSpan = document.getElementById('totalSeleccionado');
                                const montoTotalInput = document.querySelector('input[name="monto_total"]');
                                function updateTotal() {
                                    let total = 0;
                                    checkboxes.forEach(cb => {
                                        if (cb.checked) {
                                            total += parseFloat(cb.dataset.monto);
                                        }
                                    });
                                    totalSpan.textContent = total.toFixed(2);
                                    if(montoTotalInput) montoTotalInput.value = total.toFixed(2);
                                }
                                checkboxes.forEach(cb => cb.addEventListener('change', updateTotal));
                                updateTotal();
                            });
                            </script>
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
                                <button type="submit" class="btn btn-primary">
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
</script>
@endsection
