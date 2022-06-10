<?php

namespace App\Console\Commands;

use App\Models\Summoner;
use App\Traits\Functions\SummonerTrait;
use Illuminate\Console\Command;

class ReloadSummoners extends Command
{
	use SummonerTrait;
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'command:reload-summoners';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updated die Summoners.';

	public function handle(): void
	{
		$summoners = Summoner::all();

		foreach ($summoners as $summoner)
		{
			$this->reloadSummoner($summoner);
		}
	}
}
