<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('branches', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('description');
			$table->float('price');
			$table->foreignId('user_id');
			$table->dateTime('activated_at');
			$table->boolean('is_selectable');
			$table->timestamps();

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
		Schema::dropIfExists('branches');
	}
}
