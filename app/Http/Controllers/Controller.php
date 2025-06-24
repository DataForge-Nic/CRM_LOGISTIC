<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

abstract class Controller
{
    /**
     * Verifica si el usuario autenticado tiene uno de los roles dados.
     */
    protected function userHasRole($roles)
    {
        $user = Auth::user();
        if (!$user) return false;
        if (is_array($roles)) {
            return in_array($user->rol, $roles);
        }
        return $user->rol === $roles;
    }
}
