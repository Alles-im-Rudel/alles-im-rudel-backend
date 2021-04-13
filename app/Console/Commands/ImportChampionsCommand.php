<?php

namespace App\Console\Commands;

use App\Models\Champion;
use App\Models\RiotVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ImportChampionsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:champions';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importiert die Champions in die DB';

	public function handle(): void
	{
		$data = Http::get('https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/data/de_DE/champion.json')->json();

		Champion::query()->delete();

		Log::error($data);

		foreach ($data['data'] as $item) {
			Champion::create([
				'champion_id' => $item['id'],
				'version'     => $item['version'],
				'key'         => $item['key'],
				'name'        => $item['name'],
				'title'       => $item['title'],
				'blurb'       => $item['blurb'],
				'info'        => json_encode($item['info']),
				'image'       => json_encode($item['image']),
				'icon'        => $this->getIcon($item['version'], $item['id']),
				'splash_art'  => $this->getSplashArt($item['id']),
				'tags'        => json_encode($item['tags']),
				'partype'     => $item['partype'],
				'stats'       => json_encode($item['stats']),
			]);
		}


	}

	protected function getIcon(string $chamionVersion, string $championId): string
	{
		return 'https://ddragon.leagueoflegends.com/cdn/'.$chamionVersion.'/img/champion/'.$championId.'.png';
	}

	protected function getSplashArt(string $championId): string
	{
		return 'https://ddragon.leagueoflegends.com/cdn/img/champion/loading/'.$championId.'_0.jpg';
	}
}
