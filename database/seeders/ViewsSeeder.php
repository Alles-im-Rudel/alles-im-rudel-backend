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
				'body'  => '<h2>Willkommen auf <a href="allesimrudel.de" rel="noopener noreferrer" target="_blank">allesimrudel.de</a>!</h2><p>Wir sind eine immer wachsende gruppe.</p><p><br></p><h3>Entstehung: </h3><p><em>Hier kommt bisschen was zur Entstehung hin.</em></p><p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p><p><br></p><h3>Und noch mehr...:</h3><p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.</p><p><br></p><p class="ql-indent-1"><br></p>'
			],
			[
				'title' => 'Alles im Rudel Airsoft',
				'body'  => '<p>Wir sind ein wachsendes Airsoft-Team mit dem Sitz in:</p><ul><li> Südholstein/Elmshorn </li><li>Mitte Mecklenburgs an der Müritz</li></ul><h3>Ziel:</h3><p>Wir haben uns als Ziel gesetzt einzelne Airsoft-Spieler eine einfache Möglichkeit zu bieten schnell ein <strong>Team </strong>bzw. eine <strong>Gruppe </strong>zu finden, egal ob <strong>Neuling </strong>oder <strong>alter Hase</strong>.</p><p><strong>Teamplay </strong>und <strong>Spaß </strong>stehen bei uns im Vordergrund.</p>',
			],
			[
				'title' => 'Gaming',
				'body'  => '<p>Voll Gaming und so...</p>',
			]
		];
	}

}
