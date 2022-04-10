<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class BranchMembershipRejectNotification extends VerifyEmail
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Spartenaufnahmeantrag abgelehnt')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('leider müssen wir dir mitteilen, dass dein Spartenaufnahmeantrag abgelehnt wurde.')
            ->line('Viele Grüße')
            ->salutation(new HtmlString('<b>Timm, Silas & Nick</b><br>Vorstand von Alles im Rudel e.V.'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable): array
    {
        return [
            //
        ];
    }
}
