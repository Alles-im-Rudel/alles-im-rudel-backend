<?php

namespace App\Console\Commands;

use App\Models\RiotItems;
use App\Models\RiotSummonerIcon;
use App\Models\RiotVersion;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ImportItemsCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'import:items';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Importiert die Items in die DB';


	public function handle(): void
	{
		$data = Http::get('https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/data/de_DE/item.json')->json();

		RiotItems::query()->delete();
		RiotItems::create([
			'item_id' => 0
		]);
		foreach ($data['data'] as $key => $item) {
			RiotItems::create([
				'item_id'     => $key,
				'name'        => $item['name'],
				'description' => $item['description'],
				'colloq'      => $item['colloq'],
				'plaintext'   => $item['plaintext'],
				'into'        => array_key_exists('into', $item) ?? json_encode($item['into']),
				'from'        => array_key_exists('from', $item) ?? json_encode($item['from']),
				'gold'        => json_encode($item['gold']),
				'maps'        => json_encode($item['maps']),
				'stats'       => json_encode($item['stats']),
				'tags'        => json_encode($item['tags']),
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
		return 'https://ddragon.leagueoflegends.com/cdn/'.RiotVersion::getVersion().'/img/item/'.$name;
	}
}
