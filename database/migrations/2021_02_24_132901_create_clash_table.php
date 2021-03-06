<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClashTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('clash_teams', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->foreignId('leader_id')->references('id')->on('users');
			$table->timestamps();
		});

		Schema::create('clash_team_roles', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->text('image');
			$table->timestamps();
		});

		Schema::create('clash_members', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id')->references('id')->on('users');
			$table->foreignId('summoner_id')->nullable()->references('id')->on('summoners');
			$table->foreignId('clash_team_role_id')->references('id')->on('clash_team_roles');
			$table->foreignId('clash_team_id')->references('id')->on('clash_teams');
			$table->boolean('is_active');
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
		Schema::dropIfExists('clash_members');
		Schema::dropIfExists('clash_team_roles');
		Schema::dropIfExists('clash_teams');
	}
}
