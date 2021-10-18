<?php

namespace Database\Seeders;

use App\Models\Branch;

class BranchSeeder extends BaseSeeder
{
	public ?string $model = Branch::class;
	public string $firstOrCreateKey = 'name';

	public function firstOrCreate(): array
	{
		return [
			[
				'name'         => 'Alles im Rudel',
				'description'  => 'Verein uns so',
				'price'        => '1.00',
				'activated_at' => now(),
			],
			[
				'name'         => 'Airsoft',
				'description'  => 'Ganz viel Airsoft',
				'price'        => '2.00',
				'activated_at' => now(),
			],
			[
				'name'         => 'E-Sports',
				'description'  => 'Ganz viel E-Sports und Gaming',
				'price'        => '1.00',
				'activated_at' => now(),
			]
		];
	}
}
