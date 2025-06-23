<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SkylinkOne CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #1A1A5E; /* Azul oscuro */
            --secondary-color: #D7263D; /* Rojo */
            --text-color: #222222; /* Gris oscuro */
            --background-color: #FFFFFF; /* Blanco */
            --muted-bg: #F4F4F4; /* Gris claro */
            --sidebar-width: 240px;
            --sidebar-bg: #fff; /* Sidebar blanco */
            --sidebar-link: #1A1A5E; /* Azul oscuro */
            --sidebar-link-active: #D7263D; /* Rojo */
            --navbar-bg: #fff;
            --navbar-link: var(--primary-color);
            --card-bg: #fff;
            --card-border: #E9ECEF;
            --accent: var(--secondary-color);
        }
        body {
            background: var(--muted-bg);
            color: var(--text-color);
            font-family: 'Inter', Arial, sans-serif;
        }
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: var(--sidebar-link);
            box-shadow: 2px 0 10px rgba(26,26,94,0.04);
        }
        .sidebar .sidebar-logo {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 180px;
            margin-bottom: 2.5rem;
        }
        .sidebar .sidebar-logo img {
            height: 140px;
            max-width: 140px;
            width: auto;
            display: block;
        }
        .sidebar .nav-link {
            color: var(--sidebar-link);
            border-left: 4px solid transparent;
            font-weight: 500;
            font-size: 1.08rem;
            padding: 0.75rem 1.25rem;
            transition: background 0.2s, border-color 0.2s, color 0.2s;
            border-radius: 0 2rem 2rem 0;
            margin-bottom: 0.25rem;
        }
        .sidebar .nav-link.active, .sidebar .nav-link:hover {
            background: rgba(215,38,61,0.08);
            color: var(--sidebar-link-active);
            border-left: 4px solid var(--sidebar-link-active);
        }
        .sidebar .nav-link i {
            margin-right: 0.75rem;
            font-size: 1.15rem;
        }
        .navbar {
            background: var(--navbar-bg);
            color: var(--navbar-link);
            border-bottom: 1px solid var(--card-border);
        }
        .navbar .nav-link, .navbar .navbar-brand {
            color: var(--primary-color);
        }
        .navbar .nav-link.active, .navbar .nav-link:hover {
            color: var(--secondary-color);
        }
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }
        .btn-primary:hover, .btn-primary:focus {
            background: #15154a;
            border-color: #15154a;
        }
        .btn-accent, .btn-danger {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
            color: #fff;
        }
        .btn-accent:hover, .btn-danger:hover {
            background: #b81e32;
            border-color: #b81e32;
        }
        .card {
            background: var(--card-bg);
            border: 1px solid var(--card-border);
            border-radius: 1rem;
            box-shadow: 0 2px 8px rgba(26,26,94,0.04);
        }
        .card-title {
            color: var(--primary-color);
        }
        .text-accent {
            color: var(--secondary-color) !important;
        }
        .bg-accent {
            background: var(--secondary-color) !important;
            color: #fff !important;
        }
        .form-control:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.2rem rgba(215,38,61,0.15);
        }
        /* Responsive sidebar */
        @media (max-width: 991.98px) {
            .sidebar {
                width: 100%;
                position: relative;
                min-height: 60px;
            }
        }
    </style>
    @yield('head')
</head>
<body>
    @php
        $hideSidebar = request()->routeIs('facturacion.create') || request()->routeIs('facturacion.edit');
    @endphp
    <div class="d-flex">
        @if(!$hideSidebar)
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar d-flex flex-column p-3 min-vh-100">
            <div class="sidebar-logo">
                <img src="/logo_skylinkone.png" alt="SkyLink One Logo">
            </div>
            <ul class="nav nav-pills flex-column mb-auto">
                <li class="nav-item">
                    <a href="{{ route('welcome') }}" class="nav-link @if(request()->routeIs('welcome')) active @endif">
                        <i class="fas fa-home"></i> Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('clientes.index') }}" class="nav-link @if(request()->routeIs('clientes.*')) active @endif">
                        <i class="fas fa-users"></i> Clientes
                    </a>
                </li>
                <li>
                    <a href="{{ route('inventario.index') }}" class="nav-link @if(request()->routeIs('inventario.*')) active @endif">
                        <i class="fas fa-boxes"></i> Inventario
                    </a>
                </li>
                <li>
                    <a href="{{ route('facturacion.index') }}" class="nav-link @if(request()->routeIs('facturacion.*')) active @endif">
                        <i class="fas fa-file-invoice-dollar"></i> Facturación
                    </a>
                </li>
                <li>
                    <a href="{{ route('usuarios.index') }}" class="nav-link @if(request()->routeIs('usuarios.*')) active @endif">
                        <i class="fas fa-user-cog"></i> Usuarios
                    </a>
                </li>
            </ul>
        </nav>
        @endif
        <!-- Main content -->
        <div class="flex-grow-1">
            <nav class="navbar navbar-expand-lg navbar-light px-4 py-2" style="z-index:1050;">
                <div class="container-fluid">
                    <span class="navbar-brand fw-bold">@yield('page-title', 'SkylinkOne CRM')</span>
                    @if(!$hideSidebar)
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="fas fa-bell"></i></a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> Usuario
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                            </ul>
                        </li>
                    </ul>
                    @endif
                </div>
            </nav>
            <main class="p-4">
                @yield('content')
            </main>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
