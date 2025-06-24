<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogInventario extends Model
{
    use HasFactory;

    protected $table = 'logs_inventario';

    protected $fillable = [
        'user_id',
        'inventario_id',
        'accion',
        'antes',
        'despues',
    ];

    protected $casts = [
        'antes' => 'array',
        'despues' => 'array',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function inventario()
    {
        return $this->belongsTo(Inventario::class, 'inventario_id');
    }
} 