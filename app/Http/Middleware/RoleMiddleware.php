<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Maneja una solicitud entrante.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  mixed ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        $route = $request->route()->getName();
        if ($user->rol === 'basico') {
            $allowed = [
                'inventario.index', 'inventario.create', 'inventario.store', 'inventario.edit', 'inventario.update', 'inventario.show',
                'notificaciones.index', 'notificaciones.create', 'notificaciones.store', 'notificaciones.edit', 'notificaciones.update', 'notificaciones.show',
                // agrega aquí cualquier otra ruta de inventario o notificaciones
            ];
            if (!in_array($route, $allowed)) {
                abort(403, 'No tienes permiso para acceder a esta sección.');
            }
            return $next($request);
        }
        if (!$user || !in_array($user->rol, ['admin', 'auditor', 'agente'])) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }
        return $next($request);
    }
}
