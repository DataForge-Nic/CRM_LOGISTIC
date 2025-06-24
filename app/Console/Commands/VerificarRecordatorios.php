<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tracking;
use App\Models\Notificacion;
use App\Models\User;
use Carbon\Carbon;

class VerificarRecordatorios extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tracking:verificar-recordatorios';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica trackings vencidos y envía notificaciones';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Iniciando verificación de recordatorios...');

        // Buscar trackings vencidos que no estén completados
        $trackingsVencidos = Tracking::where('recordatorio_fecha', '<=', now())
            ->where('estado', '!=', 'completado')
            ->where('estado', '!=', 'vencido')
            ->with(['cliente', 'creador'])
            ->get();

        if ($trackingsVencidos->isEmpty()) {
            $this->info('No hay trackings vencidos para procesar.');
            return 0;
        }

        $this->info("Se encontraron {$trackingsVencidos->count()} trackings vencidos.");

        $usuarios = User::all();
        $notificacionesCreadas = 0;

        foreach ($trackingsVencidos as $tracking) {
            // Marcar como vencido
            $tracking->update([
                'estado' => 'vencido',
                'fecha_estado' => now()
            ]);

            // Crear notificaciones para todos los usuarios
            foreach ($usuarios as $usuario) {
                Notificacion::create([
                    'user_id' => $usuario->id,
                    'titulo' => 'Tracking Vencido',
                    'mensaje' => "El tracking {$tracking->tracking_codigo} para el cliente {$tracking->cliente->nombre} ha vencido. Fecha de vencimiento: " . Carbon::parse($tracking->recordatorio_fecha)->format('d/m/Y H:i'),
                    'leido' => false,
                    'fecha' => now(),
                ]);
                $notificacionesCreadas++;
            }

            $this->line("✓ Tracking {$tracking->tracking_codigo} marcado como vencido");
        }

        $this->info("Proceso completado. Se crearon {$notificacionesCreadas} notificaciones.");
        return 0;
    }
}
