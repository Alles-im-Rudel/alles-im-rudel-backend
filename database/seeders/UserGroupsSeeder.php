<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
		$now = Carbon::now();
		return [
			[
				'level_id'     => Level::DEVELOPER,
				'display_name' => 'Developer',
				'color'        => 'dev',
				'description'  => 'Voll krass der Developer',
			],
			[
				'level_id'     => Level::ADMINISTRATOR,
				'display_name' => 'Admin',
				'color'        => 'admin',
				'description'  => 'Voll krass der Admin',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Moderator',
				'color'        => 'moderator',
				'description'  => 'Voll krass der Moderator',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Leiter SH',
				'color'        => 'moderator',
				'description'  => 'Leiter Schlesweig-Holstein',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Leiter MV',
				'color'        => 'moderator',
				'description'  => 'Leiter Mecklenburg-Vorpommern',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'Rudel Mitglied',
				'color'        => 'member',
				'description'  => 'Voll krass das Rudel Mitglied',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'E-Sports',
				'color'        => 'eSports',
				'description'  => 'Voll krass das Rudel Esportler',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'Airsoft',
				'color'        => 'airsoft',
				'description'  => 'Voll krass der Airsoftspieler',
			],
			[
				'level_id'     => Level::PROSPECT,
				'display_name' => 'Prospect',
				'color'        => 'prospect',
				'description'  => 'Voll krass der Prospect',
			],
			[
				'level_id'     => Level::GUEST,
				'display_name' => 'Gast',
				'color'        => 'guest',
				'description'  => 'Voll krass der Gast',
			],
			[
				'level_id'     => Level::NEW,
				'display_name' => 'Neu',
				'color'        => 'new',
				'description'  => 'Voll krass der neue',
			],
		];
	}

}
