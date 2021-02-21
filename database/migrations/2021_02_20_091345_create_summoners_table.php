<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSummonersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('summoners', function (Blueprint $table) {
			$table->id();
			$table->string('account_id');
			$table->integer('profile_icon_id');
			$table->dateTime('revision_date');
			$table->string('name');
			$table->string('summoner_id');
			$table->string('puuid');
			$table->string('summoner_level');
			$table->foreignId('main_user_id')->nullable();
			$table->timestamps();
		});

		Schema::create('summoner_user', function (Blueprint $table) {
			$table->id();
			$table->foreignId('summoner_id');
			$table->foreignId('user_id');
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('summoner_user');
		Schema::dropIfExists('summoners');
	}
}
