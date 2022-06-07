<?php

namespace App\Notifications\Membership;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MembershipAcceptNotification extends Notification
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
            ->subject('Mitgliedsaufnahmeantrag angenommen')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('wir freuen uns sehr, dir mitteilen zu können, dass dein Mitgliedsaufnahmeantrag angenommen wurde und heißen dich hiermit herzlich willkommen bei Alles im Rudel e.V.!')
            ->salutation(new HtmlString('Viele Grüße<br>Timm, Silas & Nick'));
    }
}
