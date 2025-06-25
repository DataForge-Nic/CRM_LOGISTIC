@extends('layouts.app')

@section('title', 'Usuarios - SkylinkOne CRM')
@section('page-title', 'Gestión de Usuarios')

@section('content')
<div class="container-fluid px-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-users me-2 text-primary"></i>
                        Lista de Usuarios
                    </h1>
                    <p class="text-muted mb-0">Gestiona los usuarios del sistema</p>
                </div>
                <a href="{{ route('usuarios.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i>
                    Nuevo Usuario
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
                <table class="table table-hover mb-0" id="usuariosTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-user me-1 text-muted"></i>
                                Nombre
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-envelope me-1 text-muted"></i>
                                Email
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-user-tag me-1 text-muted"></i>
                                Rol
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark">
                                <i class="fas fa-toggle-on me-1 text-muted"></i>
                                Estado
                            </th>
                            <th class="border-0 px-4 py-3 fw-semibold text-dark text-center">
                                <i class="fas fa-cogs me-1 text-muted"></i>
                                Acciones
                            </th>
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
                                    <a href="{{ route('usuarios.edit', $usuario->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i> Editar
                                    </a>
                                    <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que quieres eliminar este usuario?')">
                                            <i class="fas fa-trash-alt"></i> Eliminar
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
.table thead th {
    vertical-align: middle;
}
.table td, .table th {
    vertical-align: middle;
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
