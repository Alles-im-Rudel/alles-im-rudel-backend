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
			$table->unsignedBigInteger('main_user_id')->nullable();
			$table->timestamps();

			$table->foreign('main_user_id')->references('id')->on('users');
		});

		Schema::create('summoner_user', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('summoner_id');
			$table->unsignedBigInteger('user_id');
			$table->timestamps();

			$table->foreign('summoner_id')->references('id')->on('summoners');
			$table->foreign('user_id')->references('id')->on('users');
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
