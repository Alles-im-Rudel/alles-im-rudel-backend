<?php

namespace Database\Seeders;

use App\Models\ClashTeam;
use App\Models\ClashTeamRole;
use Illuminate\Database\Seeder;

class ClashSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$clashTeam = ClashTeam::create([
			'name'      => 'Alles Im Rudel',
			'leader_id' => 3
		]);

		$clashTeamRoles = $this->clashTeamRoles();

		foreach ($clashTeamRoles as $clashTeamRole) {
			ClashTeamRole::firstOrCreate([
				'name' => $clashTeamRole['name'],
			], []);
		}

		$clashMembers = $this->clashMembers();

		foreach ($clashMembers as $clashMember) {
			$clashTeam->clashMembers()->create([
				'user_id'            => $clashMember['user_id'],
				'summoner_id'        => $clashMember['summoner_id'],
				'clash_team_role_id' => $clashMember['clash_team_role_id'],
				'is_active'          => $clashMember['is_active'],
			]);
		}
	}

	public function clashTeamRoles(): array
	{
		return [
			[
				'name' => 'Toplane',
			],
			[
				'name' => 'Jungle'
			],
			[
				'name' => 'Midlane'
			],
			[
				'name' => 'Botlane'
			],
			[
				'name' => 'Support'
			],
		];
	}

	public function clashMembers(): array
	{
		return [
			[
				'user_id'            => 2,
				'summoner_id'        => null,
				'clash_team_role_id' => 2,
				'is_active'          => true
			],
			[
				'user_id'            => 3,
				'summoner_id'        => null,
				'clash_team_role_id' => 3,
				'is_active'          => true
			],
			[
				'user_id'            => 4,
				'summoner_id'        => null,
				'clash_team_role_id' => 5,
				'is_active'          => true
			],
			[
				'user_id'            => 5,
				'summoner_id'        => null,
				'clash_team_role_id' => 4,
				'is_active'          => true
			],
			[
				'user_id'            => 6,
				'summoner_id'        => null,
				'clash_team_role_id' => 1,
				'is_active'          => true
			],
		];
	}

}
