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
				'title' => 'Alles im Rudel Gaming',
				'body'  => '<h3><span style="color: black;">Willkommen in dem Bereich Gaming von "Alles im Rudel". </span></h3><p><br></p><p><span style="color: black;">Hier möchten wir uns als E-Sports-Team an allen möglichen Spielen versuchen, und sind dabei stets auf der Suche nach weiteren Spielern, denen </span><strong style="color: black;">Teamplay </strong><span style="color: black;">und </span><strong style="color: black;">Freundlichkeit </strong><span style="color: black;">sehr wichtig sind. Bei uns steht der </span><strong style="color: black;">Spaß </strong><span style="color: black;">primär im Vordergrund, aber je nachdem, um welches Spiel es sich handelt, ist es ebenfalls ein Ziel von uns, </span><strong style="color: black;">kompetitiv </strong><span style="color: black;">an kleineren oder auch mal größeren Turnieren teilzunehmen. Die Mehrheit von uns spielt momentan vor allem </span><strong style="color: black;">League of Legends</strong><span style="color: black;">, und bei den </span><strong style="color: black;">Clash-Turnieren</strong><span style="color: black;"> sind wir regelmäßig vertreten. Im Hinblick auf </span><strong style="color: black;">League of Legends</strong><span style="color: black;"> möchten wir gerade als kurzfristiges Ziel versuchen, ein paar Teams zu bilden, die dann regelmäßig zusammenspielen und trainieren können, und letztlich an Scrims und </span><strong style="color: black;">Clash-Turnieren </strong><span style="color: black;">teilnehmen. </span></p><p><span style="color: black;">Natürlich ist aber auch </span><strong style="color: black;">jeder</strong><span style="color: black;">, der einfach nur so in seiner </span><strong style="color: black;">Freizeit </strong><span style="color: black;">ein wenig </span><strong style="color: black;">zocken </strong><span style="color: black;">möchte und auf der Suche nach einem </span><strong style="color: black;">freundlichen </strong><span style="color: black;">Team ist, bei uns herzlich willkommen!</span></p><p><br></p><p><span style="color: black;">Um noch mehr über uns zu erfahren oder uns persönlich kennenzulernen, könnt ihr hier unserem </span><a href="https://discord.com/invite/CwjhPq6" rel="noopener noreferrer" target="_blank" style="color: rgb(0, 102, 204);">Discord-Server</a><span style="color: black;"> beitreten.</span></p>',
			]
		];
	}

}
