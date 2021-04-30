<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\Permission;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$users = $this->getUsers();

		foreach ($users as $user) {
			User::firstOrCreate([
				'email' => $user['email']
			], [
				'level_id'          => $user['level_id'],
				'first_name'        => $user['first_name'],
				'last_name'         => $user['last_name'],
				'password'          => Hash::make($user['password']),
				'username'          => $user['username'],
				'email_verified_at' => $user['email_verified_at'],
				'activated_at'      => $user['activated_at'],
			]);
		}

		User::find(User::DEVELOPER_ID)->userGroups()->sync(UserGroup::DEVELOPER_ID);
	}

	public function getUsers(): array
	{
		$now = Carbon::now();
		return [
			[
				'level_id'          => Level::DEVELOPER,
				'first_name'        => 'Dev',
				'last_name'         => 'Dev',
				'username'          => 'dev',
				'password'          => '123456',
				'email'             => 'dev@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Silas',
				'last_name'         => 'Beckmann',
				'username'          => 'silas098',
				'password'          => 'changeme',
				'email'             => 'silas.beckmann@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Timm',
				'last_name'         => 'Vollborn',
				'username'          => 'RedCount99',
				'password'          => 'changeme',
				'email'             => 'timm.vollborn@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Nick',
				'last_name'         => 'Nickels',
				'username'          => 'AIR NJ',
				'password'          => 'changeme',
				'email'             => 'nick.nickels@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::MEMBER,
				'first_name'        => 'Finn',
				'last_name'         => 'Skotty',
				'username'          => 'Skotty',
				'password'          => 'changeme',
				'email'             => 'changeme@mail.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::MEMBER,
				'first_name'        => 'Till',
				'last_name'         => 'Tilldooo',
				'username'          => 'Tilldooo',
				'password'          => 'changeme',
				'email'             => 'changemeplease@mail.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
		];
	}

}
