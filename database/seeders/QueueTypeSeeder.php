<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\QueueType;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class QueueTypeSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$queueTypes = $this->getQueueTypes();

		foreach ($queueTypes as $queueType) {
			QueueType::firstOrCreate([
				'queue_type' => $queueType['queue_type']
			], [
				'display_name' => $queueType['display_name'],
			]);
		}
	}

	public function getQueueTypes(): array
	{
		return [
			[
				'queue_type'   => 'RANKED_SOLO_5x5',
				'display_name' => 'Ranked Solo/Duo 5v5'
			],
			[
				'queue_type'   => 'RANKED_TEAM_5x5',
				'display_name' => 'Ranked Flex Team 5x5'
			]
		];
	}

}
