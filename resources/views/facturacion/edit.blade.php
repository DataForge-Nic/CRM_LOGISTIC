@extends('layouts.app')

@section('title', 'Editar Factura - SkylinkOne CRM')
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
                        <h1 class="h3 mb-1 text-dark fw-bold text-start">Editar Factura</h1>
                        <p class="text-muted mb-0 text-start">Modifica los datos de la factura seleccionada</p>
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
                        <form id="factura-form" action="{{ route('facturacion.update', $factura->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Cliente</label>
                                <select name="cliente_id" class="form-select" required>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->id }}" {{ $factura->cliente_id == $cliente->id ? 'selected' : '' }}>
                                            {{ $cliente->nombre_completo }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Fecha Factura</label>
                                <input type="date" name="fecha_factura" class="form-control" value="{{ $factura->fecha_factura }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Número de Acta</label>
                                <input type="text" name="numero_acta" class="form-control" value="{{ $factura->numero_acta }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Monto Total (USD)</label>
                                <input type="number" step="0.01" name="monto_total" class="form-control" value="{{ $factura->monto_total }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Moneda</label>
                                <select name="moneda" class="form-select" required>
                                    <option value="USD" {{ $factura->moneda == 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="NIO" {{ $factura->moneda == 'NIO' ? 'selected' : '' }}>NIO</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Tasa de Cambio</label>
                                <input type="number" step="0.0001" name="tasa_cambio" class="form-control" value="{{ $factura->tasa_cambio }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Monto en moneda local</label>
                                <input type="number" step="0.01" name="monto_local" class="form-control" value="{{ $factura->monto_local }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Estado de Pago</label>
                                <select name="estado_pago" class="form-select" required>
                                    <option value="pendiente" {{ $factura->estado_pago == 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                    <option value="parcial" {{ $factura->estado_pago == 'parcial' ? 'selected' : '' }}>Parcial</option>
                                    <option value="pagado" {{ $factura->estado_pago == 'pagado' ? 'selected' : '' }}>Pagado</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nota</label>
                                <textarea name="nota" class="form-control" rows="3">{{ $factura->nota }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="delivery" class="form-label fw-semibold">Costo Delivery (opcional)</label>
                                <input type="number" step="0.01" min="0" name="delivery" id="delivery" class="form-control" value="{{ old('delivery', $factura->delivery) }}">
                            </div>

                            <div class="d-flex gap-2 justify-content-end mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Actualizar
                                </button>
                                <a href="{{ route('facturacion.index') }}" class="btn btn-secondary">Cancelar</a>
                            </div>
                        </form>
                    </div>
                    <div class="col-lg-6 p-3" style="min-width:320px;">
                        <h5 class="fw-bold mb-3"><i class="fas fa-eye text-primary me-2"></i>Previsualización</h5>
                        <iframe id="preview-pdf" src="" style="width:100%; min-height:600px; border:1px solid #e9ecef; background:#fff;"></iframe>
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
        form.querySelectorAll('textarea, select, input').forEach(function(el) {
            if (el.name && !formData.has(el.name)) {
                formData.append(el.name, el.value);
            }
        });
        var xhr = new XMLHttpRequest();
        xhr.open('POST', '{{ route('facturacion.preview-live') }}', true);
        xhr.responseType = 'blob';
        xhr.onload = function() {
            if (xhr.status === 200) {
                var url = URL.createObjectURL(xhr.response);
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
        var form = document.getElementById('factura-form');
        form.querySelectorAll('input, select, textarea').forEach(function(el) {
            var event = new Event('input', { bubbles: true });
            el.dispatchEvent(event);
        });
        setTimeout(updatePreview, 400);
    });
</script>
@endsection
