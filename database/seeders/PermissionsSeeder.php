<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\User;
use App\Models\UserGroup;
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
		foreach ($permissions as $permission) {
			Permission::firstOrCreate([
				'name' => $permission
			]);
		}

		UserGroup::find(UserGroup::DEVELOPER_ID)->syncPermissions(Permission::all()); // todo rausnehmen wenn alles geseeded wird
	}

	/**
	 * @return string[][]
	 */
	public function getPermissions(): array
	{
		return [
			// Routeheadline
			'headline.management',
			// Permissions
			'permissions.index',
			'permissions.user.sync',
			// Users
			'users.index',
			'users.update',
			'users.delete',
			'user_groups.index',
			'user_groups.user.sync',
			// LOL
			'lol_users.index',
			'lol_users.show',
			'lol_users.update',
			'lol_users.delete',
			// Summoners
			'summoners.index',
			'summoners.show',
			'summoners.main',
			'summoners.delete',
			'summoners.update',
			'summoners.reload',
			//Clash
			'clash_team.update',
			'clash_team.create',
			'clash_team.delete',
		];
	}
}
