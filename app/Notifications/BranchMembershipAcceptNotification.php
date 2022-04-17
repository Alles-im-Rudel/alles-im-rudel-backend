<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class BranchMembershipAcceptNotification extends VerifyEmail
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
            ->line('wir freuen uns sehr, dir mitteilen zu können, dass dein Spartenaufnahmeantrag angenommen wurde.')
            ->salutation(new HtmlString('Viele Grüße<br>Silas, Nick & Timm'));
    }
}
