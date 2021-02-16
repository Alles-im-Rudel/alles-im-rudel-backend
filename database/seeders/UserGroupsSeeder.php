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
				'description'  => 'Voll krass der Developer',
			],
			[
				'level_id'     => Level::ADMINISTRATOR,
				'display_name' => 'Admin',
				'description'  => 'Voll krass der Admin',
			],
			[
				'level_id'     => Level::MODERATOR,
				'display_name' => 'Moderator',
				'description'  => 'Voll krass der Moderator',
			],
			[
				'level_id'     => Level::MEMBER,
				'display_name' => 'Rudel Mitglied',
				'description'  => 'Voll krass das Rudel Mitglied',
			],
		];
	}

}
