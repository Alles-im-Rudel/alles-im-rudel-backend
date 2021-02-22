<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLeagueEntries extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('league_entries', function (Blueprint $table) {
			$table->id();
			$table->string('league_id');
			$table->string('summoner_id')->references('summoner_id')->on('summoners');
			$table->string('queue_type')->references('queue_type')->on('queue_types');
			$table->string('tier');
			$table->string('rank');
			$table->integer('league_points');
			$table->integer('wins');
			$table->integer('losses');
			$table->boolean('hot_streak');
			$table->boolean('veteran');
			$table->boolean('fresh_blood');
			$table->boolean('inactive');
			$table->timestamps();
		});

		Schema::create('queue_types', function (Blueprint $table) {
			$table->string('queue_type')->primary();
			$table->string('display_name');
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
		Schema::dropIfExists('queue_types');
		Schema::dropIfExists('league_entries');
	}
}
