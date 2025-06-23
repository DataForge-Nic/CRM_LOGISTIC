<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Servicio;

class ServicioSeeder extends Seeder
{
    public function run()
    {
        Servicio::create([
            'tipo_servicio' => 'Aéreo',
            'descripcion' => 'Envío por avión',
        ]);

        Servicio::create([
            'tipo_servicio' => 'Marítimo',
            'descripcion' => 'Envío por barco',
        ]);

        Servicio::create([
            'tipo_servicio' => 'Express',
            'descripcion' => 'Entrega rápida',
        ]);
    }
}
