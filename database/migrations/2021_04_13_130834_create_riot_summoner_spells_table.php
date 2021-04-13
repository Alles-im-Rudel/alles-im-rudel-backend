<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiotSummonerSpellsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('riot_summoner_spells', function (Blueprint $table) {
			$table->id();
			$table->string('spell_id');
			$table->string('key');
			$table->string('name');
			$table->text('description');
			$table->text('tooltip');
			$table->string('cooldown');
			$table->text('image');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('riot_summoner_spells');
	}
}
