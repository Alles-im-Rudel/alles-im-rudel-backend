<?php

namespace App\Notifications\Membership;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class MembershipLeaverNotification extends Notification
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
            ->subject('Vereinsaustrittserklärung zur Kenntnis genommen')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('wir haben deine Vereinsaustrittserklärung zur Kenntnis genommen und dein Vereinsaustritt wird somit zum INSERT-VARIABLE gültig.')
            ->line('Solltest du es dir in der Zwischenzeit anders überlegen, kannst du deine Austrittserklärung jederzeit über unsere Website zurückziehen.')
            ->salutation(new HtmlString('Viele Grüße<br>Timm, Silas & Nick'));
    }
}
