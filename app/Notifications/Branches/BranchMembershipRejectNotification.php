<?php

namespace App\Notifications\Branches;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BranchMembershipRejectNotification extends Notification
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
            ->salutation(new HtmlString('Viele Grüße<br>Timm, Silas, Nick'));
    }
}
