<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Notificacion;
use App\Models\User;

class NotificacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios existentes
        $usuarios = User::all();
        
        if ($usuarios->isEmpty()) {
            $this->command->info('No hay usuarios disponibles para crear notificaciones.');
            return;
        }

        $notificaciones = [
            [
                'titulo' => 'Bienvenido al Sistema CRM',
                'mensaje' => 'Te damos la bienvenida al sistema de gestión CRM. Aquí podrás administrar clientes, facturación e inventario de manera eficiente.',
                'leido' => false,
            ],
            [
                'titulo' => 'Nuevo Cliente Registrado',
                'mensaje' => 'Se ha registrado un nuevo cliente en el sistema. Revisa la información y completa los datos faltantes si es necesario.',
                'leido' => false,
            ],
            [
                'titulo' => 'Factura Pendiente de Pago',
                'mensaje' => 'Tienes una factura pendiente de pago que vence en los próximos días. Por favor, revisa el estado de los pagos.',
                'leido' => true,
            ],
            [
                'titulo' => 'Inventario Bajo',
                'mensaje' => 'Algunos productos en el inventario tienen stock bajo. Considera realizar un nuevo pedido para mantener el inventario actualizado.',
                'leido' => false,
            ],
            [
                'titulo' => 'Actualización del Sistema',
                'mensaje' => 'El sistema ha sido actualizado con nuevas funcionalidades. Revisa las nuevas características disponibles.',
                'leido' => true,
            ],
            [
                'titulo' => 'Respaldo Completado',
                'mensaje' => 'El respaldo automático de la base de datos se ha completado exitosamente. Todos los datos están seguros.',
                'leido' => false,
            ],
            [
                'titulo' => 'Mantenimiento Programado',
                'mensaje' => 'Se ha programado un mantenimiento del sistema para el próximo fin de semana. El servicio estará disponible nuevamente el lunes.',
                'leido' => false,
            ],
            [
                'titulo' => 'Reporte Mensual Generado',
                'mensaje' => 'El reporte mensual de ventas y facturación ha sido generado automáticamente. Puedes descargarlo desde el panel de reportes.',
                'leido' => true,
            ],
        ];

        foreach ($notificaciones as $notificacion) {
            // Asignar aleatoriamente a un usuario
            $usuario = $usuarios->random();
            
            Notificacion::create([
                'user_id' => $usuario->id,
                'titulo' => $notificacion['titulo'],
                'mensaje' => $notificacion['mensaje'],
                'leido' => $notificacion['leido'],
                'fecha' => now()->subDays(rand(1, 30))->subHours(rand(1, 24)),
            ]);
        }

        $this->command->info('Notificaciones de ejemplo creadas exitosamente.');
    }
} 