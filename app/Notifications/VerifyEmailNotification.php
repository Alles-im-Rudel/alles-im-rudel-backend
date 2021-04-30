<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable): MailMessage
	{
		$verificationUrl = $this->verificationUrl($notifiable);

		return (new MailMessage)
			->subject('Email Verifizieren')
			->greeting('Hallo '.$notifiable->first_name.' '.$notifiable->last_name.',')
			->line('bitte bestätige deine Email durch Klick auf den folgenden Button.')
			->action('Email Verifizieren', url($verificationUrl))
			->line('Gruß')
			->salutation(' Alles Im Rudel');
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
