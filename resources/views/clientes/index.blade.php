@extends('layouts.app')

@section('title', 'Clientes - SkylinkOne CRM')
@section('page-title', 'Clientes')

@section('content')
<div class="container-fluid px-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0 text-dark fw-bold">
                        <i class="fas fa-users text-primary me-2"></i>
                        Clientes
                    </h1>
                    <p class="text-muted mb-0">Gestión de clientes registrados en el sistema</p>
                </div>
                <a href="{{ route('clientes.create') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus me-1"></i> Nuevo Cliente
                </a>
            </div>
        </div>
    </div>
    <div class="card p-3">
        <div class="table-responsive">
            <table class="table align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nombre completo</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th>Tipo</th>
                        <th>Fecha registro</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($clientes as $cliente)
                        <tr>
                            <td>{{ $cliente->nombre_completo }}</td>
                            <td>{{ $cliente->correo }}</td>
                            <td>{{ $cliente->telefono }}</td>
                            <td>
                                @if($cliente->tipo_cliente == 'casillero')
                                    <span class="badge bg-accent">Casillero</span>
                                @else
                                    <span class="badge bg-primary">Normal</span>
                                @endif
                            </td>
                            <td>{{ $cliente->fecha_registro }}</td>
                            <td>
                                <a href="{{ route('clientes.edit', $cliente->id) }}" class="btn btn-sm btn-accent" title="Editar"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('clientes.destroy', $cliente->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar cliente?')" title="Eliminar"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">No hay clientes registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
