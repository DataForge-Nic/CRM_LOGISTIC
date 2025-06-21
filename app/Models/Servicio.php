<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    use HasFactory;

    protected $table = 'servicios';

    protected $fillable = [
        'tipo_servicio',
        'descripcion',
    ];

    // Relación con tarifas
    public function tarifas()
    {
        return $this->hasMany(TarifaCliente::class, 'servicio_id');
    }

    // Relación con inventario
    public function inventario()
    {
        return $this->hasMany(Inventario::class, 'servicio_id');
    }
}