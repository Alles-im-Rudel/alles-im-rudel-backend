<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Seeder;

class LevelsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		$levels = $this->getLevels();

		foreach ($levels as $level) {
			Level::firstOrCreate([
				'id' => $level['id']
			], [
				'display_name' => $level['display_name']
			]);
		}
	}

	protected function getLevels(): array
	{
		return [
			[
				'id'           => Level::DEVELOPER,
				'display_name' => 'Developer'
			],
			[
				'id'           => Level::ADMINISTRATOR,
				'display_name' => 'Administrator'
			],
			[
				'id'           => Level::MODERATOR,
				'display_name' => 'Moderator'
			],
			[
				'id'           => Level::MEMBER,
				'display_name' => 'Mitglied'
			],
		];
	}
}
