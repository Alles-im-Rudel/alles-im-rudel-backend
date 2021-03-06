<?php

namespace App\Console;

use App\Console\Commands\CheckForLeavers;
use App\Console\Commands\ImportRiotVersionsCommand;
use App\Console\Commands\ReloadSummoners;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//
	];

	/**
	 * Define the application's command schedule.
	 *
	 * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
	 * @return void
	 */
	protected function schedule(Schedule $schedule)
	{
		$schedule->command(ImportRiotVersionsCommand::class)->daily();
		$schedule->command(ReloadSummoners::class)->daily();
		$schedule->command(CheckForLeavers::class)->lastDayOfMonth('23:59');
	}

	/**
	 * Register the commands for the application.
	 *
	 * @return void
	 */
	protected function commands()
	{
		$this->load(__DIR__.'/Commands');

		require base_path('routes/console.php');
	}
}
