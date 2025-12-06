<?php

namespace App\Listeners;

use App\Events\TorneoCalificado;
use App\Notifications\TorneoCalificadoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendTorneoCalificadoNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(TorneoCalificado $event): void
    {
        $torneo = $event->torneo;

        // Notificar al organizador
        $organizador = $torneo->organizador;
        if ($organizador) {
            $organizador->notify(new TorneoCalificadoNotification($torneo));
        }

        // Notificar a todos los participantes (miembros de equipos inscritos)
        $equipos = $torneo->equipos; // Obtener todos los equipos del torneo

        foreach ($equipos as $equipo) {
            // Obtener todos los miembros de cada equipo
            $miembros = $equipo->miembros;

            foreach ($miembros as $miembro) {
                $miembro->notify(new TorneoCalificadoNotification($torneo));
            }
        }
    }
}
