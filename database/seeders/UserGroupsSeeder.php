<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserGroupsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$userGroups = $this->getUserGroups();

		foreach ($userGroups as $userGroup) {
			UserGroup::firstOrCreate([
				'display_name' => $userGroup['display_name']
			], [
				'level_id'    => $userGroup['level_id'],
				'description' => $userGroup['description'],
				'color'       => $userGroup['color'],
			]);
		}

		UserGroup::find(UserGroup::DEVELOPER_ID)->syncPermissions(Permission::all());
	}

	public function getUserGroups(): array
	{
		return [
			[
				'level_id'     => Level::DEVELOPER,
				'display_name' => 'Developer',
				'color'        => 'dev',
				'description'  => 'Offizieller Developer des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::ADMINISTRATOR,
				'display_name' => 'Vorstand',
				'color'        => 'admin',
				'description'  => 'Offizieller Vorstand des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Moderator',
				'color'        => 'moderator',
				'description'  => 'Offizieller Moderator des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Airsoft Leiter',
				'color'        => 'moderator',
				'description'  => 'Offizieller Leiter der Sparte Airsoft des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'E-Sports Leiter',
				'color'        => 'moderator',
				'description'  => 'Offizieller Leiter der Sparte E-Sports des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'Vereinsmitglied',
				'color'        => 'member',
				'description'  => 'Offizielles Mitglied des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'E-Sports Mitglied',
				'color'        => 'eSports',
				'description'  => 'Offizielles Mitglied der Sparte E-Sports des Vereins Alles im Rudel.',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'Airsoft Mitglied',
				'color'        => 'airsoft',
				'description'  => 'Offizielles Mitglied der Sparte Airsoft des Vereins Alles im Rudel.',
			],
            [
                'level_id'     => Level::PROSPECT,
                'display_name' => 'Airsoft Anwärter',
                'color'        => 'prospect',
                'description'  => 'Offizieller Anwärter der Sparte Airsoft des Vereins Alles im Rudel.',
            ],
            [
                'level_id'     => Level::PROSPECT,
                'display_name' => 'E-Sports Anwärter',
                'color'        => 'prospect',
                'description'  => 'Offizieller Anwärter der Sparte E-Sports des Vereins Alles im Rudel.',
            ],
            [
                'level_id'     => Level::GUEST,
                'display_name' => 'Freund',
                'color'        => 'guest',
                'description'  => 'Freund.',
            ],
            [
                'level_id'     => Level::GUEST,
                'display_name' => 'Gast',
                'color'        => 'guest',
                'description'  => 'Gast.',
            ]
		];
	}

}
