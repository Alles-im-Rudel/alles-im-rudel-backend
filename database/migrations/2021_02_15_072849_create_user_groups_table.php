<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserGroupsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('user_groups', function (Blueprint $table) {
			$table->id();
			$table->foreignId('level_id')->default(1);
			$table->string('display_name')->nullable();
			$table->string('description')->nullable();
			$table->softDeletes();
			$table->timestamps();

			$table->foreign('level_id')->references('id')->on('levels');
		});

		Schema::create('user_user_group', static function (Blueprint $table) {
			$table->foreignId('user_id');
			$table->foreignId('user_group_id');

			$table->unique(['user_id', 'user_group_id']);

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('user_group_id')->references('id')->on('user_groups');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_user_group');
		Schema::dropIfExists('user_groups');
	}
}
