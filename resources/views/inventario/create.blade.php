@extends('layouts.app')

@section('title', 'Nuevo Paquete - SkylinkOne CRM')
@section('page-title', 'Registrar Nuevo Paquete')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-plus-circle me-2 text-primary"></i>
                        Registrar Nuevo Paquete
                    </h1>
                    <p class="text-muted mb-0">Completa la información del paquete para agregarlo al inventario</p>
                </div>
                <div>
                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i>
                        Volver al Inventario
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-box me-2 text-primary"></i>
                        Información del Paquete
                    </h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('inventario.store') }}" method="POST" id="inventarioForm">
                        @csrf

                        <div class="row g-4">
                            <!-- Cliente -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cliente_id" class="form-label fw-semibold">
                                        <i class="fas fa-user me-1 text-muted"></i>
                                        Cliente *
                                    </label>
                                    <select name="cliente_id" class="form-select @error('cliente_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un cliente</option>
                                        @foreach($clientes as $cliente)
                                            <option value="{{ $cliente->id }}" {{ old('cliente_id') == $cliente->id ? 'selected' : '' }}>
                                                {{ $cliente->nombre_completo }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('cliente_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Servicio -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="servicio_id" class="form-label fw-semibold">
                                        <i class="fas fa-shipping-fast me-1 text-muted"></i>
                                        Servicio *
                                    </label>
                                    <select name="servicio_id" class="form-select @error('servicio_id') is-invalid @enderror" required>
                                        <option value="">Seleccione un servicio</option>
                                        @foreach($servicios as $servicio)
                                            <option value="{{ $servicio->id }}" {{ old('servicio_id') == $servicio->id ? 'selected' : '' }}>
                                                {{ $servicio->tipo_servicio }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('servicio_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Peso -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="peso_lb" class="form-label fw-semibold">
                                        <i class="fas fa-weight-hanging me-1 text-muted"></i>
                                        Peso (lb) *
                                    </label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" name="peso_lb" class="form-control @error('peso_lb') is-invalid @enderror" 
                                               value="{{ old('peso_lb') }}" required placeholder="0.00">
                                        <span class="input-group-text">lb</span>
                                    </div>
                                    @error('peso_lb')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tracking -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tracking_codigo" class="form-label fw-semibold">
                                        <i class="fas fa-barcode me-1 text-muted"></i>
                                        Código de Tracking
                                    </label>
                                    <input type="text" name="tracking_codigo" class="form-control @error('tracking_codigo') is-invalid @enderror" value="{{ old('tracking_codigo') }}" placeholder="Ingrese el código de tracking">
                                    @error('tracking_codigo')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Número de Guía -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="numero_guia" class="form-label fw-semibold">
                                        <i class="fas fa-barcode me-1 text-muted"></i>
                                        Número de Guía
                                    </label>
                                    <input type="text" name="numero_guia" class="form-control @error('numero_guia') is-invalid @enderror" 
                                           value="{{ old('numero_guia') }}" placeholder="Ingrese el número de guía">
                                    @error('numero_guia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Estado -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado" class="form-label fw-semibold">
                                        <i class="fas fa-info-circle me-1 text-muted"></i>
                                        Estado *
                                    </label>
                                    <select name="estado" class="form-select @error('estado') is-invalid @enderror" required>
                                        <option value="">Seleccione el estado</option>
                                        <option value="recibido" {{ old('estado') == 'recibido' ? 'selected' : '' }}>
                                            📦 Recibido
                                        </option>
                                        <option value="en_transito" {{ old('estado') == 'en_transito' ? 'selected' : '' }}>
                                            🚚 En Tránsito
                                        </option>
                                        <option value="entregado" {{ old('estado') == 'entregado' ? 'selected' : '' }}>
                                            ✅ Entregado
                                        </option>
                                        <option value="en_oficina" {{ old('estado') == 'en_oficina' ? 'selected' : '' }}>
                                            🏢 En Oficina
                                        </option>
                                    </select>
                                    @error('estado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tarifa Manual -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tarifa_manual" class="form-label fw-semibold">
                                        <i class="fas fa-dollar-sign me-1 text-muted"></i>
                                        Tarifa Manual (opcional)
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="tarifa_manual" class="form-control @error('tarifa_manual') is-invalid @enderror" 
                                               value="{{ old('tarifa_manual') }}" placeholder="0.00">
                                    </div>
                                    <small class="form-text text-muted">Dejar vacío para calcular automáticamente</small>
                                    @error('tarifa_manual')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Monto Calculado -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="monto_calculado" class="form-label fw-semibold">
                                        <i class="fas fa-calculator me-1 text-muted"></i>
                                        Monto Calculado ($) *
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">$</span>
                                        <input type="number" step="0.01" name="monto_calculado" class="form-control @error('monto_calculado') is-invalid @enderror" 
                                               value="{{ old('monto_calculado') }}" required placeholder="0.00">
                                    </div>
                                    @error('monto_calculado')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Notas -->
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="notas" class="form-label fw-semibold">
                                        <i class="fas fa-sticky-note me-1 text-muted"></i>
                                        Notas Adicionales
                                    </label>
                                    <textarea name="notas" class="form-control @error('notas') is-invalid @enderror" 
                                              rows="4" placeholder="Información adicional sobre el paquete...">{{ old('notas') }}</textarea>
                                    @error('notas')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <hr class="my-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('inventario.index') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>
                                        Guardar Paquete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar Info -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-info-circle me-2 text-info"></i>
                        Información Útil
                    </h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="fw-semibold text-dark mb-2">
                            <i class="fas fa-lightbulb text-warning me-1"></i>
                            Consejos
                        </h6>
                        <ul class="list-unstyled small text-muted">
                            <li class="mb-2">• Asegúrate de verificar el peso y volumen</li>
                            <li class="mb-2">• El número de guía es opcional pero recomendado</li>
                            <li class="mb-2">• Las notas ayudan a identificar el paquete</li>
                            <li>• El estado se puede actualizar después</li>
                        </ul>
                    </div>
                    
                    <div class="mb-3">
                        <h6 class="fw-semibold text-dark mb-2">
                            <i class="fas fa-calculator text-primary me-1"></i>
                            Cálculo de Tarifa
                        </h6>
                        <p class="small text-muted mb-0">
                            La tarifa se calcula automáticamente basada en el peso, volumen y tipo de servicio seleccionado.
                        </p>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="mb-0 fw-semibold text-dark">
                        <i class="fas fa-clock text-success me-2"></i>
                        Estados Disponibles
                    </h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-success bg-opacity-10 text-success me-2">📦</span>
                        <small class="text-muted">Recibido - Paquete en almacén</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-warning bg-opacity-10 text-warning me-2">🚚</span>
                        <small class="text-muted">En Tránsito - En camino</small>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <span class="badge bg-primary bg-opacity-10 text-primary me-2">✅</span>
                        <small class="text-muted">Entregado - Completado</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary me-2">🏢</span>
                        <small class="text-muted">En Oficina - Pendiente de envío</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
}

.card {
    transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.form-label {
    color: #495057;
}

.input-group-text {
    background-color: #f8f9fa;
    border-color: #dee2e6;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate monto based on peso and servicio
    const pesoInput = document.querySelector('input[name="peso_lb"]');
    const servicioSelect = document.querySelector('select[name="servicio_id"]');
    const clienteSelect = document.querySelector('select[name="cliente_id"]');
    const montoInput = document.querySelector('input[name="monto_calculado"]');
    const tarifaManualInput = document.querySelector('input[name="tarifa_manual"]');
    
    function calculateMonto() {
        const peso = parseFloat(pesoInput.value) || 0;
        let rate = 0;
        if (tarifaManualInput.value) {
            rate = parseFloat(tarifaManualInput.value) || 0;
        } else if (clienteSelect.value && servicioSelect.value) {
            rate = parseFloat(tarifaManualInput.value) || 0;
        } else {
            rate = 1.00;
        }
        const monto = peso * rate;
        montoInput.value = monto.toFixed(2);
    }
    
    pesoInput.addEventListener('input', calculateMonto);
    servicioSelect.addEventListener('change', function() {
        obtenerTarifaCliente();
        calculateMonto();
    });
    clienteSelect.addEventListener('change', function() {
        obtenerTarifaCliente();
        calculateMonto();
    });
    tarifaManualInput.addEventListener('input', calculateMonto);

    function obtenerTarifaCliente() {
        const clienteId = clienteSelect.value;
        const servicioId = servicioSelect.value;
        if (clienteId && servicioId) {
            fetch("{{ route('inventario.obtener-tarifa') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                },
                body: JSON.stringify({ cliente_id: clienteId, servicio_id: servicioId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.tarifa !== null) {
                    tarifaManualInput.value = data.tarifa;
                } else {
                    tarifaManualInput.value = '';
                }
                calculateMonto();
            });
        } else {
            tarifaManualInput.value = '';
            calculateMonto();
        }
    }
    
    // Form validation
    const form = document.getElementById('inventarioForm');
    form.addEventListener('submit', function(e) {
        const requiredFields = form.querySelectorAll('[required]');
        let isValid = true;
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                field.classList.add('is-invalid');
                isValid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        if (!isValid) {
            e.preventDefault();
            alert('Por favor, completa todos los campos requeridos.');
        }
    });
});
</script>
@endsection
