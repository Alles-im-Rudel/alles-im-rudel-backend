<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$this->call(LevelsSeeder::class);
		$this->call(PermissionsSeeder::class);
		$this->call(UserGroupsSeeder::class);
		$this->call(UsersSeeder::class);
		$this->call(QueueTypeSeeder::class);
		$this->call(ClashSeeder::class);
		$this->call(TagSeeder::class);
		$this->call(ViewsSeeder::class);
	}
}
