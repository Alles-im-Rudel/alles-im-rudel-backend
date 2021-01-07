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
		];
	}
}
