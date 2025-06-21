<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cliente extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nombre_completo',
        'correo',
        'telefono',
        'direccion',
        'tipo_cliente',
        'fecha_registro',
        'created_by',
        'updated_by',
    ];

    // Relaciones
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function facturas()
    {
        return $this->hasMany(Facturacion::class);
    }
}