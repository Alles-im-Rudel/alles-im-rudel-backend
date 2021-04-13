<?php

namespace App\Listeners;

use App\Events\BirthdayChanged;
use App\Models\Appointment;

class BirthdayChangeAppointment
{
	/**
	 * Handle the event.
	 *
	 * @param  BirthdayChanged  $event
	 * @return void
	 */
	public function handle(BirthdayChanged $event): void
	{
		$user = $event->getUser();

		Appointment::updateOrCreate([
			'birthday_id' => $user->id
		], [
			'title'       => 'Geburstag',
			'text'        => null,
			'start_at'    => $user->birthday,
			'end_at'      => $user->birthday,
			'is_all_day'  => true,
			'is_birthday' => true,
			'birthday_id' => $user->id,
			'user_id'     => $user->id,
		]);
	}
}
