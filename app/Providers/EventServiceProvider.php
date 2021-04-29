<?php

namespace App\Providers;

use App\Events\BirthdayChanged;
use App\Listeners\BirthdayChangeAppointment;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		BirthdayChanged::class => [
			BirthdayChangeAppointment::class
		]

	];
}
