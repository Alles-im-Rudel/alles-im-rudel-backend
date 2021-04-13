<?php

namespace App\Console\Commands;

use App\Models\RiotSummonerSpell;
use App\Models\RiotVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportSummonerSpellsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:summoner-spells';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importiert die Summoner Spells in die DB';

	public function handle(): void
	{
		$data = Http::get('https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/data/de_DE/summoner.json')->json();

		RiotSummonerSpell::query()->delete();

		foreach ($data['data'] as $item) {
			RiotSummonerSpell::create([
				'key'         => $item['key'],
				'spell_id'    => $item['id'],
				'name'        => $item['name'],
				'description' => $item['description'],
				'tooltip'     => $item['tooltip'],
				'cooldown'    => $item['cooldownBurn'],
				'image'       => $this->getImage($item['image']['full'])
			]);
		}
	}

	/**
	 * @param  string  $name
	 * @return string
	 */
	protected function getImage(string $name): string
	{
		return 'https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/img/spell/'.$name;
	}
}
