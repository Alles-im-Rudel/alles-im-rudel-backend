<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserGroup;
use App\Models\View;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ViewsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$views = $this->getViews();

		foreach ($views as $view) {
			View::firstOrCreate([
				'title' => $view['title']
			], [
				'body' => $view['body'],
			]);
		}

	}

	public function getViews(): array
	{
		return [
			[
				'title' => 'Alles im Rudel',
				'body'  => 'Voll Alles im Rudel Startseite...'
			],
			[
				'title' => 'Alles im Rudel Airsoft',
				'body'  => 'Wir sind ein wachsendes Airsoft-Team mit dem Sitz in Südholstein/Elmshorn und Mitte Mecklenburgs an der
           					Müritz. Wir haben uns als Ziel gesetzt einzelne Airsoft-Spieler eine einfache Möglichkeit zu bieten schnell
          					ein Team bzw. eine Gruppe zu finden, egal ob Neuling oder alter Hase.
							Teamplay und Spaß stehen bei uns im Vordergrund.',
			],
			[
				'title' => 'Gaming',
				'body'  => 'Voll Gaming und so...',
			]
		];
	}

}
