<?php

namespace App\Notifications;

use App\Models\Torneo;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class TorneoCalificadoNotification extends Notification
{
    use Queueable;

    protected $torneo;

    /**
     * Create a new notification instance.
     */
    public function __construct(Torneo $torneo)
    {
        $this->torneo = $torneo;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('El torneo ha sido finalizado revisa la evaluación')
                    ->greeting('Hola ' . $notifiable->name)
                    ->line('El torneo "' . $this->torneo->name . '" ha sido calificado por todos los jueces.')
                    ->action('Ver torneo', url('https://universodev.online/login' . $this->torneo->id))
                    ->line('¡Gracias por usar nuestra plataforma!');
    }
}
