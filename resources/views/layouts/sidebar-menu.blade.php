{{-- resources/views/layouts/sidebar-menu.blade.php --}}
<ul class="nav flex-column">
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/') }}"><i class="fas fa-home"></i> Dashboard</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/inventario') }}"><i class="fas fa-box"></i> Inventario</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/facturacion') }}"><i class="fas fa-file-invoice"></i> Facturación</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/tracking') }}"><i class="fas fa-search"></i> Tracking</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/notificaciones') }}"><i class="fas fa-bell"></i> Notificaciones</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" href="{{ url('/usuarios') }}"><i class="fas fa-users"></i> Usuarios</a>
    </li>
    @if($user && $user->rol === 'admin')
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/logs-inventario') }}">
                <i class="fas fa-history"></i> Historial de Inventario
            </a>
        </li>
    @endif
    <!-- Agrega más enlaces según tu app -->
</ul> 