<?php

namespace Database\Seeders;

use App\Models\QueueType;

class QueueTypeSeeder extends BaseSeeder
{
    public ?string $model =  QueueType::class;
    public string $firstOrCreateKey = 'queue_type';

	public function firstOrCreate(): array
	{
		return [
			[
				'queue_type'   => 'RANKED_SOLO_5x5',
				'display_name' => 'Ranked Solo/Duo 5v5'
			],
			[
				'queue_type'   => 'RANKED_TEAM_5x5',
				'display_name' => 'Ranked Flex Team 5x5'
			],
			[
				'queue_type'   => 'RANKED_FLEX_SR',
				'display_name' => 'Ranked Flex 5v5'
			]
		];
	}

}
