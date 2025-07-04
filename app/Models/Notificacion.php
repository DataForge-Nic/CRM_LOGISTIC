<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notificacion extends Model
{
    use HasFactory;

    protected $table = 'notificaciones';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'titulo',
        'mensaje',
        'leido',
        'fecha',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}