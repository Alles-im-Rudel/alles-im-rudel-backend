<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class MembershipRejectNotification extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Mitgliedsaufnahmeantrag abgelehnt')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('wir möchten dir für dein Interesse am Beitritt zu Alles im Rudel e.V. danken.')
            ->line('Leider müssen wir dir mitteilen, dass dein Mitgliedsaufnahmeantrag abgelehnt wurde.')
            ->salutation(new HtmlString('Viele Grüße<br>Silas, Nick & Timm'));
    }
}
