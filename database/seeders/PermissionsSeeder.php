<?php

namespace Database\Seeders;

use App\Models\Permission;
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
			'permissions.user_groups.sync',
			// Users
			'users.index',
            'users.store',
			'users.update',
			'users.show',
			'users.delete',
			'users.like',
			// UserGroups
			'user_groups.index',
			'user_groups.show',
			'user_groups.update',
			'user_groups.delete',
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
			'clash.update',
			'clash_team.update',
			'clash_team.create',
			'clash_team.delete',
			// Comments
			'comments.create',
			'comments.delete',
			'comments.update',
			'comments.like',
			// Post
			'posts.create',
			'posts.delete',
			'posts.update',
			'posts.like',
			// Views
			'views.update',
			// Appointments
			'appointments.create',
			'appointments.update',
			'appointments.delete',
			'appointments.like',
			// Dashboard
			'dashboard.index'
		];
	}
}
