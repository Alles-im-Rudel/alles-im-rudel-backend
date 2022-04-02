<?php

namespace Database\Seeders;

use App\Models\Level;
use App\Models\User;
use App\Models\UserGroup;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends BaseSeeder
{
	public ?string $model = User::class;
	public string $updateOrCreateKey = 'email';

	public function afterwards(): void
	{
		User::find(User::DEVELOPER_ID)->userGroups()->sync(UserGroup::DEVELOPER_ID);
	}

	public function updateOrCreate(): array
	{
		$now = Carbon::now();
		$changeMe = Hash::make('changeMe');

		return [
			[
				'level_id'          => Level::DEVELOPER,
				'first_name'        => 'Dev',
				'last_name'         => 'Dev',
				'password'          => $changeMe,
				'email'             => 'allesimrudel@gmail.com',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Silas',
				'last_name'         => 'Beckmann',
				'password'          => $changeMe,
				'email'             => 'silas.beckmann@t-online.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Timm',
				'last_name'         => 'Vollborn',
				'password'          => $changeMe,
				'email'             => 'timm.vollborn@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
			[
				'level_id'          => Level::ADMINISTRATOR,
				'first_name'        => 'Nick',
				'last_name'         => 'Nickels',
				'password'          => $changeMe,
				'email'             => 'nick.nickels@allesimrudel.de',
				'email_verified_at' => $now,
				'activated_at'      => $now,
			],
		];
	}
}
