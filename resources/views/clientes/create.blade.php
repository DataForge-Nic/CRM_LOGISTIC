@extends('layouts.app')

@section('title', 'Registrar Cliente - SkylinkOne CRM')
@section('page-title', 'Registrar Cliente')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                        <i class="fas fa-user-plus text-primary" style="font-size:2.2rem;"></i>
                    </div>
                    <div>
                        <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Registrar Cliente</h1>
                        <p class="mb-0 text-white-50" style="font-size:1.1rem;">Completa los datos para registrar un nuevo cliente</p>
                    </div>
                </div>
                <a href="{{ route('clientes.index') }}" class="btn btn-lg fw-semibold shadow-sm px-4" style="background:#fff; color:#1A2E75; border:2px solid #1A2E75; box-shadow:0 2px 8px rgba(26,46,117,0.08); font-size:1.2rem;">
                    <i class="fas fa-arrow-left me-2"></i> Volver
                </a>
            </div>
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card p-4">
                <div class="row g-0 align-items-stretch">
                    <form id="cliente-form" method="POST" action="#" class="d-flex flex-wrap">
                        @csrf
                        <div class="col-lg-6 p-3 border-end" style="min-width:320px;">
                            <div class="mb-3">
                                <label for="nombre_completo" class="form-label fw-semibold">Nombre completo</label>
                                <input type="text" class="form-control form-control-lg rounded-3" id="nombre_completo" name="nombre_completo" required>
                            </div>
                            <div class="mb-3">
                                <label for="correo" class="form-label fw-semibold">Correo</label>
                                <input type="email" class="form-control form-control-lg rounded-3" id="correo" name="correo">
                            </div>
                            <div class="mb-3">
                                <label for="telefono" class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control form-control-lg rounded-3" id="telefono" name="telefono">
                            </div>
                            <div class="mb-3">
                                <label for="direccion" class="form-label fw-semibold">Dirección</label>
                                <textarea class="form-control form-control-lg rounded-3" id="direccion" name="direccion" rows="2"></textarea>
                            </div>
                            <div class="mb-4">
                                <label for="tipo_cliente" class="form-label fw-semibold">Tipo de Cliente</label>
                                <select class="form-select form-select-lg rounded-3" id="tipo_cliente" name="tipo_cliente">
                                    <option value="Normal">Normal</option>
                                    <option value="VIP">VIP</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 p-3 d-flex flex-column justify-content-between" style="min-width:320px;">
                            <div>
                                <h5 class="fw-bold mb-3"><i class="fas fa-dollar-sign text-primary me-2"></i>Tarifas por Servicio</h5>
                                <div class="mb-3 d-flex align-items-center gap-3">
                                    <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="fas fa-plane text-primary"></i></span>
                                    <label class="form-label mb-0 flex-grow-1">Aéreo <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control tarifa-input" id="tarifa_aereo" name="tarifa_aereo" placeholder="0.00" required style="max-width:120px;">
                                </div>
                                <div class="mb-3 d-flex align-items-center gap-3">
                                    <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="fas fa-ship text-primary"></i></span>
                                    <label class="form-label mb-0 flex-grow-1">Marítimo <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control tarifa-input" id="tarifa_maritimo" name="tarifa_maritimo" placeholder="0.00" required style="max-width:120px;">
                                </div>
                                <div class="mb-3 d-flex align-items-center gap-3">
                                    <span class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width:38px; height:38px;"><i class="fas fa-bolt text-primary"></i></span>
                                    <label class="form-label mb-0 flex-grow-1">Pie Cúbico</label>
                                    <input type="number" step="0.01" min="0" class="form-control tarifa-input" id="tarifa_pie_cubico" name="tarifa_pie_cubico" placeholder="0.00" style="max-width:120px;">
                                </div>
                            </div>
                            <button type="button" class="btn btn-success w-100 mt-4 align-self-end" id="btn_finalizar_registro" disabled><i class="fas fa-check"></i> Registrar Cliente</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
let clienteFormData = null;

// Mantener solo la validación de campos obligatorios
function checkCamposObligatorios() {
    const form = document.getElementById('cliente-form');
    let valid = true;
    form.querySelectorAll('[required]').forEach(function(input) {
        if (!input.value.trim()) {
            valid = false;
        }
    });
    document.getElementById('btn_finalizar_registro').disabled = !valid;
}
document.querySelectorAll('#cliente-form [required]').forEach(inp => {
    inp.addEventListener('input', checkCamposObligatorios);
});
checkCamposObligatorios();

document.getElementById('btn_finalizar_registro').addEventListener('click', function() {
    const form = document.getElementById('cliente-form');
    clienteFormData = new FormData(form);
    fetch('/clientes', {
        method: 'POST',
        body: clienteFormData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.id) {
            alert('Cliente y tarifas registradas correctamente.');
            window.location.href = '/clientes';
        } else {
            alert('Error al guardar el cliente.');
        }
    });
});
</script>
<style>
    .card {
        border-radius: 18px;
        box-shadow: 0 2px 8px rgba(26,46,117,0.04);
    }
    .form-control, .form-select {
        border-radius: 8px !important;
        font-size: 1.08rem;
        padding: 0.7rem 1.1rem;
        border: 1.5px solid #e3e8f0;
        background: #f8fafc;
        color: #1A2E75;
        font-weight: 500;
        box-shadow: none;
        transition: border 0.18s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #5C6AC4;
        outline: none;
        background: #fff;
    }
    .btn-primary {
        background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%);
        border: none;
        color: #fff;
        font-weight: 700;
        border-radius: 8px;
    }
    .btn-primary:hover, .btn-primary:focus {
        background: linear-gradient(90deg, #5C6AC4 0%, #1A2E75 100%);
        color: #fff;
    }
    .btn-outline-secondary {
        border-radius: 8px;
        border: 1.5px solid #bfc7d8;
        color: #6c7a92;
        background: #f8fafc;
        font-weight: 600;
    }
    .btn-outline-secondary:hover, .btn-outline-secondary:focus {
        background: #e3e8f0;
        color: #1A2E75;
    }
</style>
@endsection
