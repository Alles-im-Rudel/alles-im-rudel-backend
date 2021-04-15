<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRiotItemsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('riot_items', function (Blueprint $table) {
			$table->id();
			$table->integer('item_id');
			$table->string('name');
			$table->text('description');
			$table->text('plaintext');
			$table->text('into')->nullable();
			$table->text('from')->nullable();
			$table->text('tags');
			$table->text('maps');
			$table->text('gold');
			$table->text('stats');
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
		Schema::dropIfExists('riot_items');
	}
}
