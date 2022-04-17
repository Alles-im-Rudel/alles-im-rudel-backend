<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('E-Mail verifizieren')
            ->greeting('Hallo ' . $notifiable->first_name . ',')
            ->line(new HtmlString('dein Beitrittsantrag ist erfolgreich bei uns eingegangen!<br>Bitte verifiziere deine E-Mail, um den Antrag zu bestätigen.'))
            ->action('E-Mail verifizieren', $verificationUrl)
            ->salutation(new HtmlString('Viele Grüße<br>Silas, Nick & Timm'));
    }
}
