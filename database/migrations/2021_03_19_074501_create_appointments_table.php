<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('appointments', function (Blueprint $table) {
			$table->id();
			$table->string('title');
			$table->longText('text')->nullable();
			$table->dateTime('start_at');
			$table->dateTime('end_at');
			$table->string('color')->default('primary');
			$table->boolean('is_all_day');
			$table->boolean('is_birthday')->default(false);
			$table->foreignId('birthday_id')->nullable()->references('id')->on('users');
			$table->foreignId('user_id')->references('id')->on('users');
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
		Schema::dropIfExists('appointments');
	}
}
