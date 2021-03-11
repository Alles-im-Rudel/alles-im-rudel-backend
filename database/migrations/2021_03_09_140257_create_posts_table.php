<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('tags', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('color');
			$table->timestamps();
		});

		Schema::create('posts', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->longText('text');
			$table->foreignId('user_id')->references('id')->on('users');
			$table->timestamps();
		});

		Schema::create('comments', function (Blueprint $table) {
			$table->id();
			$table->text('text');
			$table->foreignId('user_id')->references('id')->on('users');
			$table->foreignId('post_id')->references('id')->on('posts');
			$table->timestamps();
		});

		Schema::create('post_tag', function (Blueprint $table) {
			$table->id();
			$table->foreignId('post_id')->references('id')->on('posts')->onDelete('cascade');
			$table->foreignId('tag_id')->references('id')->on('tags')->onDelete('cascade');
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
		Schema::dropIfExists('post_tags');
		Schema::dropIfExists('comments');
		Schema::dropIfExists('posts');
		Schema::dropIfExists('tags');
	}
}
