<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class NewAppointmentNotification extends Notification
{
    use Queueable;

    protected Appointment $appointment;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Appointment $post)
    {
        $this->appointment = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Neues Event')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('es wurde ein neues Event mit dem Titel "' . $this->appointment->title . '" erstellt!')
            ->action('Zum Kalender', env('APP_FRONTEND_URL') . '/calendar')
            ->salutation(new HtmlString('Viele Grüße<br>Silas, Nick & Timm'));
    }
}
