<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserAccountNotification extends Notification 
{
    use Queueable;

    public function __construct(
        public $user,
        public $admin,
        public $action // 'juez_creado', 'usuario_creado' o 'usuario_eliminado'
    ) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $message = (new MailMessage)
            ->subject($this->getSubject())
            ->greeting('¡Hola, ' . $this->user->name . '!');

        if ($this->action === 'usuario_eliminado') {
            $message->line('Te informamos que tu cuenta en **UniversoDev** ha sido eliminada por el ' . $this->admin->name . '.')
                ->line('**Información de la cuenta eliminada:**')
                ->line('Correo: ' . $this->user->email)
                ->line('Rol: ' . $this->user->rol)
                ->line('Si crees que esto es un error o deseas más información, por favor contacta al administrador.')
                ->line('Gracias por haber sido parte de nuestra comunidad.')
                ->line('Equipo UniversoDev');
        } else {
            $message->line($this->getMainMessage())
                ->line('Tu cuenta ha sido creada ');

            if ($this->action === 'juez_creado') {
                $message->line('**Como juez, ahora puedes:**')
                    ->line('- Evaluar proyectos en torneos asignados')
                    ->line('- Calificar trabajos de participantes')
                    ->line('- Contribuir al crecimiento de la comunidad');
            } else {
                $message->line('**Ahora puedes:**')
                    ->line('- Crear y gestionar proyectos increíbles')
                    ->line('- Unirte y formar equipos de desarrollo')
                    ->line('- Participar en torneos y competencias')
                    ->line('- Conectar con otros desarrolladores');
            }

            $message->line('**Información de tu cuenta:**')
                ->line('Correo: ' . $this->user->email)
                ->line('Rol: ' . $this->user->rol)
                ->action('Acceder a la Plataforma', url('/'))
                ->line('¡Bienvenido a UniversoDev!')
                ->line('Contraseña de acceso: '.$this->user->password);
        }

        return $message;
    }

    private function getSubject()
    {
        return match($this->action) {
            'juez_creado' => '¡Bienvenido como Juez en UniversoDev!',
            'usuario_creado' => '¡Bienvenido a UniversoDev!',
            'usuario_eliminado' => 'Tu cuenta ha sido eliminada',
            default => 'Notificación de UniversoDev',
        };
    }

    private function getMainMessage()
    {
        return match($this->action) {
            'juez_creado' => 'Te damos la bienvenida como **Juez** en la plataforma UniversoDev.',
            'usuario_creado' => '¡Bienvenido a la comunidad de desarrolladores más innovadora!',
            default => '',
        };
    }
}