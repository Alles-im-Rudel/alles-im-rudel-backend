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
				'level_id'          => Level::DEVELOPER,
				'first_name'        => 'Test',
				'last_name'         => 'test',
				'username'          => 'test',
				'password'          => '123456',
				'email'             => 'test@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
		];
	}

}
