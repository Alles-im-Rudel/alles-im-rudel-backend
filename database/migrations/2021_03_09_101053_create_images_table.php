<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('images', function (Blueprint $table) {
			$table->bigIncrements('id');

			$table->morphs('imageable');

			$table->longText('image');
			$table->longText('thumbnail');
			$table->string('file_name')->nullable();
			$table->string('title')->nullable();
			$table->integer('file_size');
			$table->string('file_mime_type');

			$table->softDeletes();
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
		Schema::dropIfExists('images');
	}
}
