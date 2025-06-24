<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tracking;
use App\Models\Cliente;
use App\Models\User;
use Carbon\Carbon;

class TrackingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener clientes y usuarios existentes
        $clientes = Cliente::all();
        $usuarios = User::all();
        
        if ($clientes->isEmpty() || $usuarios->isEmpty()) {
            $this->command->info('No hay clientes o usuarios disponibles para crear trackings.');
            return;
        }

        $trackings = [
            [
                'tracking_codigo' => 'TRK-2024-001',
                'estado' => 'pendiente',
                'recordatorio_fecha' => now()->addHours(2),
                'nota' => 'Seguimiento de envío urgente para cliente premium',
            ],
            [
                'tracking_codigo' => 'TRK-2024-002',
                'estado' => 'en_proceso',
                'recordatorio_fecha' => now()->addDays(1),
                'nota' => 'Verificación de documentación pendiente',
            ],
            [
                'tracking_codigo' => 'TRK-2024-003',
                'estado' => 'completado',
                'recordatorio_fecha' => now()->subDays(1),
                'nota' => 'Envío completado exitosamente',
            ],
            [
                'tracking_codigo' => 'TRK-2024-004',
                'estado' => 'pendiente',
                'recordatorio_fecha' => now()->addHours(6),
                'nota' => 'Esperando confirmación del cliente',
            ],
            [
                'tracking_codigo' => 'TRK-2024-005',
                'estado' => 'vencido',
                'recordatorio_fecha' => now()->subHours(3),
                'nota' => 'No se pudo contactar al cliente',
            ],
            [
                'tracking_codigo' => 'TRK-2024-006',
                'estado' => 'pendiente',
                'recordatorio_fecha' => now()->addDays(3),
                'nota' => 'Seguimiento de facturación pendiente',
            ],
            [
                'tracking_codigo' => 'TRK-2024-007',
                'estado' => 'en_proceso',
                'recordatorio_fecha' => now()->addHours(12),
                'nota' => 'Procesando documentación aduanera',
            ],
            [
                'tracking_codigo' => 'TRK-2024-008',
                'estado' => 'pendiente',
                'recordatorio_fecha' => now()->addDays(7),
                'nota' => 'Seguimiento de inventario mensual',
            ],
        ];

        foreach ($trackings as $tracking) {
            // Asignar aleatoriamente a un cliente y usuario
            $cliente = $clientes->random();
            $usuario = $usuarios->random();
            
            Tracking::create([
                'cliente_id' => $cliente->id,
                'tracking_codigo' => $tracking['tracking_codigo'],
                'estado' => $tracking['estado'],
                'fecha_estado' => now(),
                'recordatorio_fecha' => $tracking['recordatorio_fecha'],
                'nota' => $tracking['nota'],
                'creado_por' => $usuario->id,
            ]);
        }

        $this->command->info('Trackings de ejemplo creados exitosamente.');
    }
} 