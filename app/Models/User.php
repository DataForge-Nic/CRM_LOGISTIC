<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Atributos que pueden ser asignados masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nombre',
        'email',
        'password',
        'rol',
        'estado',
    ];

    /**
     * Atributos ocultos para arrays/JSON.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Conversión de tipos de atributos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'estado' => 'boolean',
        'password' => 'hashed',
    ];

    public function username()
    {
        return 'nombre';
    }
}
