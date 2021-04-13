<?php

namespace App\Console\Commands;

use App\Models\RiotSummonerIcon;
use App\Models\RiotVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportSummonerIconsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:summoner-icons';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importiert die Summoner Icons in die DB';


	public function handle(): void
	{
		$data = Http::get('https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/data/de_DE/profileicon.json')->json();

		RiotSummonerIcon::query()->delete();

		foreach ($data['data'] as $item) {
			RiotSummonerIcon::create([
				'name'  => $item['id'],
				'image' => $this->getImage($item['image']['full'])
			]);
		}
	}

	/**
	 * @param  string  $name
	 * @return string
	 */
	protected function getImage(string $name): string
	{
		return 'https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/img/profileicon/'.$name;
	}
}
