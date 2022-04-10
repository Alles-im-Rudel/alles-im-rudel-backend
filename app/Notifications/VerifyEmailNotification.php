<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

class VerifyEmailNotification extends VerifyEmail
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
	 * @param  mixed  $notifiable
	 * @return MailMessage
	 */
	public function toMail($notifiable): MailMessage
	{
		$verificationUrl = $this->verificationUrl($notifiable);

		return (new MailMessage)
			->subject('E-Mail verifizieren')
			->greeting('Hallo '.$notifiable->first_name.',')
			->line(new HtmlString('vielen Dank für dein Interesse an einem Beitritt zu Alles im Rudel e.V.<br><br>Dein Mitgliedsaufnahmeantrag ist bei uns eingegangen und wird nun von uns bearbeitet. Sobald dieser durch uns bearbeitet wurde, benachrichtigen wir dich automatisch per E-Mail.<br><br>Bitte bestätige noch deine E-Mail durch Klick auf den folgenden Button.'))
			->action('E-Mail verifizieren', url($verificationUrl))
			->line('Viele Grüße')
			->salutation(new HtmlString('<b>Timm, Silas & Nick</b><br>Vorstand von Alles im Rudel e.V.'));
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable): array
	{
		return [
			//
		];
	}
}
