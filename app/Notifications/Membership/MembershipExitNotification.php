<?php

namespace App\Notifications\Membership;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MembershipExitNotification extends Notification
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
            ->subject('Vereinsaustritt erfolgreich')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('dein Vereinsaustritt ist nun wirksam.')
            ->line('Schicke uns gerne wieder einen Vereinsaufnahmeantrag über unsere Website, wenn du wieder Lust hast mit dabei zu sein.')
            ->salutation(new HtmlString('Schweren Herzens<br>Timm, Silas & Nick'));
    }
}
