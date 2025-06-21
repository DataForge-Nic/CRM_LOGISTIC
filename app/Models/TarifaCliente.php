<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifaCliente extends Model
{
    use HasFactory;

    protected $table = 'tarifas_clientes';

    protected $fillable = [
        'cliente_id',
        'servicio_id',
        'tarifa',
    ];

    // Cliente relacionado
    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    // Servicio relacionado
    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}