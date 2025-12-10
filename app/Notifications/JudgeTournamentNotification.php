<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class JudgeTournamentNotification extends Notification 
{
    use Queueable;

    public function __construct(
        public $juez,
        public $torneo,
        public $admin,
        public $action // 'asignado' o 'removido'
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('¡Hola, ' . $this->juez->name . '!')
            ->line($this->getMainMessage());

        if ($this->action === 'asignado') {
            $message->line('**Información del torneo:**')
                ->line('Nombre: ' . $this->torneo->name)
                ->line('Inicio: ' . $this->torneo->fecha_inicio->format('d/m/Y'))
                ->line('Fin: ' . $this->torneo->fecha_fin->format('d/m/Y'))
                ->line('Organizador: ' . $this->torneo->organizador->name);

            if ($this->torneo->descripcion) {
                $message->line('Descripción: ' . $this->torneo->descripcion);
            }

            $message->line('**Tus responsabilidades:**')
                ->line('- Evaluar los proyectos participantes')
                ->line('- Calificar según los criterios establecidos')
                ->line('- Proporcionar retroalimentación constructiva')
                ->line('- Mantener la objetividad e imparcialidad')
                ->action('Ver Torneo', url('/torneos/' . $this->torneo->id))
                ->line('Gracias por tu colaboración en hacer de este torneo un éxito.');
        } else {
            $message->line('**Información del torneo:**')
                ->line('Nombre: ' . $this->torneo->name)
                ->line('Inicio: ' . $this->torneo->fecha_inicio->format('d/m/Y'))
                ->line('Fin: ' . $this->torneo->fecha_fin->format('d/m/Y'))
                ->line('Esta decisión puede deberse a:')
                ->line('Cambios en la organización del torneo')
                ->line('Reasignación de jueces')
                ->line('Ajustes administrativos')
                ->line('Tu trabajo como juez es muy valorado y esperamos contar contigo en futuros torneos.')
                ->action('Ir a la Plataforma', url('/'));
        }

        return $message;
    }

    private function getSubject()
    {
        return $this->action === 'asignado'
            ? 'Nueva Asignación de Torneo: ' . $this->torneo->name
            : 'ℹActualización de Asignación: ' . $this->torneo->name;
    }

    private function getMainMessage()
    {
        return $this->action === 'asignado'
            ? 'Has sido asignado como **juez** al torneo "' . $this->torneo->name . '" por ' . $this->admin->name . '.'
            : 'Has sido **removido** como juez del torneo "' . $this->torneo->name . '" por ' . $this->admin->name . '.';
    }
}