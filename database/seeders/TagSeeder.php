<?php

namespace Database\Seeders;


use App\Models\Tag;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$tags = $this->getTags();

		foreach ($tags as $tag) {
			Tag::firstOrCreate([
				'name' => $tag['name']
			], [
				'color' => $tag['color'],
			]);
		}
	}

	public function getTags(): array
	{
		return [
			[
				'name'  => 'Airsoft',
				'color' => '#4D4832'
			],
			[
				'name'  => 'Allgemein',
				'color' => '#B8B8B5'
			],
			[
				'name'  => 'E-Sports',
				'color' => '#0362fc'
			]
		];
	}

}
