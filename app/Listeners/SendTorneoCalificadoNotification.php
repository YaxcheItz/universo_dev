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
        $organizador = $torneo->organizador; // asumimos relación organizador()

        if ($organizador) {
            $organizador->notify(new TorneoCalificadoNotification($torneo));
        }
    }
}
