{{-- resources/views/layouts/navbar-menu.blade.php --}}
<style>
    .logout-link {
        color: inherit;
        text-decoration: none;
        font-size: 1rem;
        transition: color 0.2s;
        display: flex;
        align-items: center;
        gap: 0.4rem;
    }
    .logout-link:hover {
        color: #D7263D !important;
    }
    .navbar-user {
        font-weight: 500;
        margin-right: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.4rem;
        font-size: 1rem;
    }
    .navbar-user i, .logout-link i {
        font-size: 1em;
        vertical-align: middle;
    }
    .navbar-flex {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        width: 100%;
        font-size: 1rem;
    }
    .navbar-flex form {
        margin: 0;
        padding: 0;
        display: flex;
        align-items: center;
    }
</style>
<div class="navbar-flex">
    <span class="navbar-user">
        <i class="fas fa-user"></i> {{ $user->nombre ?? 'Usuario' }}
    </span>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="logout-link">
            <i class="fas fa-sign-out-alt"></i> Cerrar sesión
        </button>
    </form>
</div> 