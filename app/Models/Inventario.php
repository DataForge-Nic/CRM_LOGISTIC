<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    use HasFactory;

    protected $table = 'inventario';

    protected $fillable = [
        'cliente_id',
        'peso_lb',
        'volumen_pie3',
        'tarifa_manual',
        'factura_id',
        'monto_calculado',
        'fecha_ingreso',
        'estado',
        'numero_guia',
        'notas',
        'servicio_id',
    ];

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }

    public function factura()
    {
        return $this->belongsTo(Facturacion::class, 'factura_id');
    }

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }
}