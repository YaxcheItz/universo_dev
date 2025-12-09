<?php

namespace App\Listeners;

use App\Events\TorneoCalificado;
use App\Notifications\TorneoCalificadoNotification;

class SendTorneoCalificadoNotification
{
    /**
     * Handle the event.
     */
    public function handle(TorneoCalificado $event): void
    {

        $torneo = $event->torneo;
        $organizador = $torneo->organizador;

        // Recopilar todos los usuarios únicos que deben ser notificados
        $usuariosANotificar = collect();

        // Agregar organizador
        if ($organizador) {
            $usuariosANotificar->push($organizador);
        }

        // Obtener todos los equipos del torneo
        $equipos = $torneo->equipos;

        // Agregar líderes y miembros de equipos participantes
        foreach ($equipos as $equipo) {
            // Agregar líder del equipo
            if ($equipo->lider) {
                $usuariosANotificar->push($equipo->lider);
            }

            // Agregar todos los miembros del equipo
            foreach ($equipo->miembros as $miembro) {
                $usuariosANotificar->push($miembro);
            }
        }

        // Eliminar usuarios duplicados por ID y notificar a cada uno una sola vez
        $usuariosANotificar->unique('id')->each(function ($usuario) use ($torneo) {
            $usuario->notify(new TorneoCalificadoNotification($torneo));
        });
    }
}
