<?php

namespace App\Notifications\Branches;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class BranchMembershipExitNotification extends Notification
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
            ->subject('Spartenaustrittserklärung erfolgreich')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line('dein Spartenaustritt ist nun wirksam.')
            ->line('Schicke uns gerne wieder einen Spartenaufnahmeantrag über unsere Website, wenn du wieder Lust hast mit dabei zu sein.')
            ->salutation(new HtmlString('Schweren Herzens<br>Timm, Silas & Nick'));
    }
}
