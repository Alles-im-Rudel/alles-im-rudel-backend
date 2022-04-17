<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class BranchMembershipRejectNotification extends VerifyEmail
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
            ->subject('Spartenaufnahmeantrag abgelehnt')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('leider müssen wir dir mitteilen, dass dein Spartenaufnahmeantrag abgelehnt wurde.')
            ->salutation(new HtmlString('Viele Grüße<br>Silas, Nick & Timm'));
    }
}
