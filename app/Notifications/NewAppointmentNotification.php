<?php

namespace App\Notifications;

use App\Models\Appointment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewAppointmentNotification extends Notification
{
	use Queueable;

	protected Appointment $appointment;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(Appointment $post)
	{
		$this->appointment = $post;
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function via($notifiable): array
	{
		return ['mail'];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($notifiable): MailMessage
	{
		return (new MailMessage)
			->subject('Neuer Post')
			->greeting('Hallo '.$notifiable->first_name.' '.$notifiable->last_name.',')
			->line('es wurde ein neues Event "'.$this->appointment->title.'" erstellt mit den Kategorien:')
			->line($this->getTags())
			->action('Zum Event', env('APP_FRONTEND_URL').'calendar')
			->line('Gruß')
			->salutation(' Alles Im Rudel');
	}

	/**
	 * Get the array representation of the notification.
	 *
	 * @param  mixed  $notifiable
	 * @return array
	 */
	public function toArray($notifiable)
	{
		return [
			//
		];
	}

	/**
	 * @return string
	 */
	protected function getTags(): string
	{
		$tags = '';

		foreach ($this->post->tags as $tag) {
			$tags .= $tag->name.' ';
		}
		return $tags;
	}
}
