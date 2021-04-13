<?php

namespace App\Console\Commands;

use App\Models\RiotSummonerIcon;
use App\Models\RiotVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

class ImportRiotVersionsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:riot-versions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importiert die Riot Version in die DB';

	public function handle(): void
	{

		$versions = Http::get('https://ddragon.leagueoflegends.com/api/versions.json')->json();

		RiotVersion::query()->delete();

		RiotVersion::create([
			'version' => $versions[0]
		]);

		Artisan::call('import:champions');
		Artisan::call('import:summoner-icons');
		Artisan::call('import:summoner-spells');
	}
}
