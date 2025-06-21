<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    use HasFactory;

    protected $table = 'tracking';

    protected $fillable = [
        'cliente_id',
        'tracking_codigo',
        'estado',
        'fecha_estado',
        'recordatorio_fecha',
        'nota',
        'creado_por',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function creador()
    {
        return $this->belongsTo(User::class, 'creado_por');
    }
}