{{-- resources/views/layouts/navbar-menu.blade.php --}}
<style>
    .logout-link {
        color: inherit;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.2s;
    }
    .logout-link:hover {
        color: #D7263D !important; /* Rojo del logo */
    }
</style>
<div class="d-flex align-items-center justify-content-end w-100" style="font-size: 1rem;">
    <span class="d-flex align-items-center">
        <i class="fas fa-user me-1"></i> {{ $user->nombre ?? 'Usuario' }}
    </span>
    <form method="POST" action="{{ route('logout') }}" class="ms-3" style="display:inline;">
        @csrf
        <button type="submit" class="btn btn-link nav-link p-0 m-0 logout-link" style="display:inline;">
            <i class="fas fa-sign-out-alt me-1"></i> Cerrar sesión
        </button>
    </form>
</div> 