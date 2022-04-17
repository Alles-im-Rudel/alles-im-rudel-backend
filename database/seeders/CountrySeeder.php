<?php

namespace Database\Seeders;

use App\Models\Country;

class CountrySeeder extends BaseSeeder
{
	public ?string $model = Country::class;
	public string $firstOrCreateKey = 'name';

	public function firstOrCreate(): array
	{
		return [
			[
				'name' => 'Deutschland',
				'iso_code'  => 'DE'
			]
		];
	}
}
