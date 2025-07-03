@extends('layouts.app')

@section('title', 'Usuarios - SkylinkOne CRM')
@section('page-title', 'Gestión de Usuarios')

@if(auth()->check() && auth()->user()->rol === 'admin')
    @section('content')
    <div class="container-fluid px-4">
        <!-- Header Section -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="rounded-4 shadow-sm px-4 py-4 mb-4 d-flex align-items-center justify-content-between" style="background: linear-gradient(90deg, #1A2E75 0%, #5C6AC4 100%); min-height:90px;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center" style="width:60px; height:60px; box-shadow:0 2px 8px rgba(0,0,0,0.08);">
                            <i class="fas fa-users text-primary" style="font-size:2.2rem;"></i>
                        </div>
                        <div>
                            <h1 class="h3 mb-1 fw-bold text-white" style="letter-spacing:1px;">Lista de Usuarios</h1>
                            <p class="mb-0 text-white-50" style="font-size:1.1rem;">Gestiona los usuarios del sistema</p>
                        </div>
                    </div>
                    <a href="{{ route('usuarios.create') }}" class="btn btn-lg fw-semibold shadow-sm px-4" style="background:#1A2E75; color:#fff;">
                        <i class="fas fa-user-plus me-2"></i> Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Filters Section -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6 mb-2">
                <input type="text" class="form-control" id="searchInput" placeholder="Buscar por nombre, email o rol...">
            </div>
            <div class="col-lg-3 col-md-6 mb-2">
                <select class="form-select" id="roleFilter">
                    <option value="">Todos los roles</option>
                    <option value="admin">Administrador</option>
                    <option value="agente">Agente</option>
                    <option value="contador">Contador</option>
                </select>
            </div>
            <div class="col-lg-3 col-md-6 mb-2">
                <select class="form-select" id="statusFilter">
                    <option value="">Todos los estados</option>
                    <option value="activo">Activo</option>
                    <option value="inactivo">Inactivo</option>
                </select>
            </div>
            <div class="col-lg-2 col-md-6 mb-2 d-flex align-items-end">
                <button class="btn btn-outline-primary w-100" id="clearFilters">
                    <i class="fas fa-times me-1"></i>
                    Limpiar
                </button>
            </div>
        </div>

        <!-- Main Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="mb-0 fw-semibold text-dark">
                    <i class="fas fa-list me-2 text-primary"></i>
                    Usuarios Registrados
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table usuarios-table table-hover align-middle mb-0" id="usuariosTable">
                        <thead class="table-light">
                            <tr>
                                <th class="border-0 px-4 py-3 fw-semibold text-dark"><i class="fas fa-user me-1 text-muted"></i>Nombre</th>
                                <th class="border-0 px-4 py-3 fw-semibold text-dark"><i class="fas fa-envelope me-1 text-muted"></i>Email</th>
                                <th class="border-0 px-4 py-3 fw-semibold text-dark"><i class="fas fa-user-tag me-1 text-muted"></i>Rol</th>
                                <th class="border-0 px-4 py-3 fw-semibold text-dark"><i class="fas fa-toggle-on me-1 text-muted"></i>Estado</th>
                                <th class="border-0 px-4 py-3 fw-semibold text-dark text-center"><i class="fas fa-cogs me-1 text-muted"></i>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($usuarios as $usuario)
                                <tr>
                                    <td class="px-4 py-3 align-middle">{{ $usuario->nombre }}</td>
                                    <td class="px-4 py-3 align-middle">{{ $usuario->email }}</td>
                                    <td class="px-4 py-3 align-middle text-capitalize">{{ $usuario->rol }}</td>
                                    <td class="px-4 py-3 align-middle">
                                        @if($usuario->estado)
                                            <span class="badge bg-success bg-opacity-10 text-success">Activo</span>
                                        @else
                                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Inactivo</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 align-middle text-center">
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-inv-action btn-inv-edit me-1">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-inv-action btn-inv-delete" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="5" class="text-center">No hay usuarios registrados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <style>
    .usuarios-table {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(26,46,117,0.04);
        border-collapse: separate;
        border-spacing: 0;
    }
    .usuarios-table thead th {
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
    .usuarios-table thead th i {
        color: #fff !important;
        font-size: 1.15em;
        margin-right: 4px;
        vertical-align: middle;
        display: inline-block;
    }
    .usuarios-table thead th:first-child {
        border-top-left-radius: 16px;
    }
    .usuarios-table thead th:last-child {
        border-top-right-radius: 16px;
    }
    .usuarios-table thead tr {
        border-radius: 0 !important;
    }
    .usuarios-table tbody tr {
        background: #fff;
        transition: background 0.2s;
        border-bottom: 1.5px solid #e3e6f0;
    }
    .usuarios-table tbody td {
        border: none !important;
        padding: 10px 14px !important;
        vertical-align: middle !important;
        font-size: 1.01rem;
    }
    .usuarios-table tbody tr:hover {
        background: #F0F4FF !important;
    }
    .btn-inv-action {
        border-radius: 8px !important;
        min-width: 34px;
        min-height: 34px;
        padding: 0 10px;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        box-shadow: none;
        transition: background 0.15s;
        margin-right: 4px;
    }
    .btn-inv-action:last-child {
        margin-right: 0;
    }
    .btn-inv-edit {
        background: #5C6AC4;
        color: #fff;
    }
    .btn-inv-delete {
        background: #BF1E2E;
        color: #fff;
    }
    .btn-inv-action:hover, .btn-inv-action:focus {
        opacity: 0.92;
        color: #fff;
    }
    .card {
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
    }
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const statusFilter = document.getElementById('statusFilter');
        const clearFilters = document.getElementById('clearFilters');
        const table = document.getElementById('usuariosTable');

        function filterTable() {
            const search = searchInput.value.toLowerCase();
            const role = roleFilter.value;
            const status = statusFilter.value;
            for (let row of table.tBodies[0].rows) {
                const nombre = row.cells[0].textContent.toLowerCase();
                const email = row.cells[1].textContent.toLowerCase();
                const rol = row.cells[2].textContent.toLowerCase();
                const estado = row.cells[3].textContent.toLowerCase();
                let show = true;
                if (search && !nombre.includes(search) && !email.includes(search) && !rol.includes(search)) show = false;
                if (role && rol !== role) show = false;
                if (status) {
                    if (status === 'activo' && estado !== 'activo') show = false;
                    if (status === 'inactivo' && estado !== 'inactivo') show = false;
                }
                row.style.display = show ? '' : 'none';
            }
        }

        searchInput.addEventListener('input', filterTable);
        roleFilter.addEventListener('change', filterTable);
        statusFilter.addEventListener('change', filterTable);
        clearFilters.addEventListener('click', function() {
            searchInput.value = '';
            roleFilter.value = '';
            statusFilter.value = '';
            filterTable();
        });
    });
    </script>
    @endsection
@else
    @section('content')
    <div class="alert alert-danger mt-5">No tienes permiso para acceder a este módulo.</div>
    @endsection
@endif
