<?php

namespace App\Notifications;

use App\Models\Inscripcion;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InscripcionCreada extends Notification
{
    use Queueable;
    protected $inscripcion;
    /**
     * Create a new notification instance.
     */
    public function __construct(Inscripcion $inscripcion)
    {
        $this->inscripcion = $inscripcion;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->line('Se ha preinscrito a la inscripcion del estudiante ' . $this->inscripcion->estudiante->name . ' ' . $this->inscripcion->estudiante->apellido)
                    ->action('Ver inscripción', url('http://localhost:5173/index/inscripciones'))
                    ->line('Gracias por utilizar el sistema!');
    }

    public function toDatabase($notifiable)
    {
        return [
            'inscripcion_id' => $this->inscripcion->id,
            'mensaje' => $this->inscripcion->estudiante->name . ' ' . $this->inscripcion->estudiante->apellido . ', se ha preinscrito.',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray($notifiable)
    {
        return [
            'inscripcion_id' => $this->inscripcion->id,
            'mensaje' => $this->inscripcion->estudiante->name . ' ' . $this->inscripcion->estudiante->apellido . ', se ha preinscrito.',
        ];
    }
}
