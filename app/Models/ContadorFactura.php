<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContadorFactura extends Model
{
    use HasFactory;

    protected $table = 'contador_facturas';
    public $timestamps = false;
    public $incrementing = false;

    protected $primaryKey = 'id';
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'ultimo_numero',
    ];
}