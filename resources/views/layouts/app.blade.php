<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SkylinkOne CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery (debe ir antes de select2 y de cualquier script que use $) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
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
        /* Notificaciones */
        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--secondary-color);
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            font-size: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .notification-dropdown {
            min-width: 300px;
            max-height: 400px;
            overflow-y: auto;
        }
        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: #f8f9fa;
        }
        .notification-item.unread {
            background-color: #fff3cd;
        }
        .notification-title {
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .notification-message {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }
        .notification-time {
            font-size: 11px;
            color: #999;
        }
        /* Responsive sidebar */
        @media (max-width: 991.98px) {
            #sidebar {
                position: static !important;
                width: 100% !important;
                height: auto !important;
                border-radius: 0;
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            .sidebar-logo img {
                height: 70px !important;
                max-width: 120px;
            }
            .flex-grow-1 {
                margin-left: 0 !important;
            }
        }
    </style>
    @yield('head')
</head>
<body>
    @php
        $hideSidebar = request()->routeIs('facturacion.create') || request()->routeIs('facturacion.edit');
        $user = Auth::user();
        // Para desarrollo sin login, simular usuario admin
        if (!$user) {
            $user = (object)[
                'nombre' => 'Usuario',
                'rol' => 'admin',
            ];
        }
    @endphp
    <div class="d-flex">
        @if(!$hideSidebar && !request()->routeIs('inventario.edit'))
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar d-flex flex-column p-3" style="background: #fff; box-shadow: 2px 0 10px rgba(26,46,117,0.04); position: fixed; top: 0; left: 0; height: 100vh; width: 260px; z-index: 1040;">
            <div class="sidebar-logo d-flex justify-content-center align-items-center" style="background: #fff; border-radius: 1.5rem; margin-bottom: 2.5rem; padding-top: 3.5rem; padding-bottom: 3.5rem;">
                <img src="/logo_skylinkone.png" alt="SkyLink One Logo" style="height: 200px; max-width: 260px; width: auto; display: block;">
            </div>
            <ul class="nav nav-pills flex-column mb-auto gap-2">
                <li class="nav-item">
                    <a href="{{ route('welcome') }}" class="nav-link @if(request()->routeIs('welcome')) active @endif sidebar-link">
                        <i class="fas fa-home"></i> <span class="sidebar-link-text">Dashboard</span>
                    </a>
                </li>
                @if($user && in_array($user->rol, ['admin', 'contador', 'agente']))
                <li>
                    <a href="{{ route('clientes.index') }}" class="nav-link @if(request()->routeIs('clientes.*')) active @endif sidebar-link">
                        <i class="fas fa-users"></i> <span class="sidebar-link-text">Clientes</span>
                    </a>
                </li>
                @endif
                @if($user && in_array($user->rol, ['admin', 'agente']))
                <li>
                    <a href="{{ route('inventario.index') }}" class="nav-link @if(request()->routeIs('inventario.*')) active @endif sidebar-link">
                        <i class="fas fa-boxes"></i> <span class="sidebar-link-text">Inventario</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('tracking.dashboard') }}" class="nav-link @if(request()->routeIs('tracking.*')) active @endif sidebar-link">
                        <i class="fas fa-stopwatch"></i> <span class="sidebar-link-text">Tracking</span>
                    </a>
                </li>
                @if($user && in_array($user->rol, ['admin', 'contador']))
                <li>
                    <a href="{{ route('facturacion.index') }}" class="nav-link @if(request()->routeIs('facturacion.*')) active @endif sidebar-link">
                        <i class="fas fa-file-invoice-dollar"></i> <span class="sidebar-link-text">Facturación</span>
                    </a>
                </li>
                @endif
                @if($user && in_array($user->rol, ['admin', 'contador', 'agente']))
                <li>
                    <a href="{{ route('notificaciones.index') }}" class="nav-link @if(request()->routeIs('notificaciones.*')) active @endif sidebar-link">
                        <i class="fas fa-bell"></i> <span class="sidebar-link-text">Notificaciones</span>
                    </a>
                </li>
                @endif
                @if($user && $user->rol === 'admin')
                <li>
                    <a href="{{ route('usuarios.index') }}" class="nav-link @if(request()->routeIs('usuarios.*')) active @endif sidebar-link">
                        <i class="fas fa-user-cog"></i> <span class="sidebar-link-text">Usuarios</span>
                    </a>
                </li>
                <li style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    <a href="{{ route('logs_inventario.index') }}" class="nav-link @if(request()->routeIs('logs_inventario.*')) active @endif sidebar-link" style="display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-history"></i>
                        <span class="sidebar-link-text" style="font-size: 1rem;">Historial Inventario</span>
                    </a>
                </li>
                @endif
            </ul>
        </nav>
        @endif
        <!-- Main content -->
        <div class="flex-grow-1" style="margin-left: 260px;">
            <nav class="navbar navbar-expand-lg px-4 py-2 sticky-top shadow-sm" style="background: #f4f6fb; color: #1A2E75; z-index:1050;">
                <div class="container-fluid">
                    @if(!$hideSidebar)
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-center gap-3">
                        <li class="nav-item dropdown position-relative">
                            <a class="nav-link position-relative" style="color: #1A2E75;" href="#" id="notificationDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-bell"></i>
                                <span class="notification-badge" id="notificationCount" style="background:#BF1E2E; color:#fff;">0</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationDropdown">
                                <li><h6 class="dropdown-header">Notificaciones</h6></li>
                                <div id="notificationList">
                                    <li class="dropdown-item text-center text-muted">
                                        <small>Cargando notificaciones...</small>
                                    </li>
                                </div>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-center" href="{{ route('notificaciones.index') }}">Ver todas</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" style="color: #1A2E75;" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-user-circle"></i> {{ $user->nombre ?? 'Usuario' }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#">Perfil</a></li>
                                @if(Route::has('logout'))
                                <li class="dropdown-item">
                                    <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline text-danger" style="text-decoration:none;">Cerrar sesión</button>
                                    </form>
                                </li>
                                @endif
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Cargar notificaciones no leídas
        function loadNotifications() {
            fetch('{{ route("notificaciones.no-leidas") }}')
                .then(response => response.json())
                .then(data => {
                    const notificationList = document.getElementById('notificationList');
                    const notificationCount = document.getElementById('notificationCount');
                    
                    if (data.length === 0) {
                        notificationList.innerHTML = '<li class="dropdown-item text-center text-muted"><small>No hay notificaciones nuevas</small></li>';
                        notificationCount.style.display = 'none';
                    } else {
                        notificationCount.textContent = data.length;
                        notificationCount.style.display = 'flex';
                        
                        notificationList.innerHTML = data.map(notification => `
                            <li class="dropdown-item notification-item unread" onclick="markAsRead(${notification.id})">
                                <div class="notification-title">${notification.titulo}</div>
                                <div class="notification-message">${notification.mensaje.substring(0, 100)}${notification.mensaje.length > 100 ? '...' : ''}</div>
                                <div class="notification-time">${new Date(notification.fecha).toLocaleString()}</div>
                            </li>
                        `).join('');
                    }
                })
                .catch(error => {
                    console.error('Error loading notifications:', error);
                });
        }

        // Marcar notificación como leída
        function markAsRead(notificationId) {
            fetch(`/notificaciones/${notificationId}/marcar-leida`, {
                method: 'PATCH',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadNotifications();
                }
            })
            .catch(error => {
                console.error('Error marking notification as read:', error);
            });
        }

        // Cargar notificaciones al cargar la página
        document.addEventListener('DOMContentLoaded', function() {
            loadNotifications();
            
            // Recargar notificaciones cada 30 segundos
            setInterval(loadNotifications, 30000);
        });
    </script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>
