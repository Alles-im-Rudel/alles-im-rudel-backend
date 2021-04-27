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
			$table->integer('item_id')->nullable();
			$table->string('name')->nullable();
			$table->text('description')->nullable();
			$table->text('plaintext')->nullable();
			$table->text('into')->nullable();
			$table->text('from')->nullable();
			$table->text('tags')->nullable();
			$table->text('maps')->nullable();
			$table->text('gold')->nullable();
			$table->text('stats')->nullable();
			$table->text('image')->nullable();
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
