<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';

    protected $fillable = [
        'factura_id',
        'monto_pagado',
        'fecha_pago',
        'metodo_pago',
        'referencia',
    ];

    public function factura()
    {
        return $this->belongsTo(Facturacion::class, 'factura_id');
    }
}