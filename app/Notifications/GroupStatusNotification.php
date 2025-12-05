<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
class GroupStatusNotification extends Notification
{
    public function __construct(
        public $group,
        public $status
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Estado de tu solicitud')
            ->line(
                $this->status === 'accepted'
                ? '¡Has sido aceptado en el grupo! ' . $this->group->name.' en UniversoDev.'
                : 'Tu solicitud al grupo ' . $this->group->name . ' fue rechazada en UniversoDev.'
            );
    }
}
