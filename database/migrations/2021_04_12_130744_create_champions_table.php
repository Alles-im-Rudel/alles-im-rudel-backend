<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChampionsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('champions', function (Blueprint $table) {
			$table->id();
			$table->string('champion_id');
			$table->string('version');
			$table->string('key');
			$table->string('name');
			$table->string('title');
			$table->text('blurb');
			$table->text('info');
			$table->text('image');
			$table->text('icon');
			$table->text('splash_art');
			$table->text('tags');
			$table->string('partype');
			$table->text('stats');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('champions');
	}
}
