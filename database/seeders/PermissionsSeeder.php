<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use Illuminate\Database\Seeder;

class PermissionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$permissions = $this->getPermissions();
		$permissionsNameArray = [];

		foreach ($permissions as $permission) {
			Permission::firstOrCreate([
				'name' => $permission['name']
			]);

			$permissionsNameArray[] = $permission['name'];
		}

		$devUser = User::where('id', '=', User::DEVELOPER_ID)->first();
		if ($devUser) {
			$devUser->givePermissionTo($permissionsNameArray);
		}
	}

	/**
	 * @return string[][]
	 */
	public function getPermissions(): array
	{
		return [
			// Routeheadline
			[
				'name' => 'headline.management'
			],
			// Permissions
			[
				'name' => 'permissions.index'
			],

			// Users
			[
				'name' => 'users.index'
			],
			[
				'name' => 'users.update'
			],
			[
				'name' => 'users.delete'
			],
			[
				'name' => 'permissions.index'
			],
			[
				'name' => 'permissions.user.sync'
			],
			[
				'name' => 'user_groups.index'
			],
			[
				'name' => 'user_groups.user.sync'
			],
			[
				'name' => 'lol_users.index'
			],
			[
				'name' => 'lol_users.show'
			],
			[
				'name' => 'lol_users.update'
			],
			[
				'name' => 'lol_users.delete'
			],
			[
				'name' => 'summoners.index'
			],
			[
				'name' => 'summoners.show'
			],
			[
				'name' => 'summoners.delete'
			],
			[
				'name' => 'summoners.update'
			],
			[
				'name' => 'summoners.reload'
			]
		];
	}
}
