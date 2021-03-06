<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('users', function (Blueprint $table) {
			$table->id();
			$table->foreignId('level_id')->default(1);
			$table->foreignId('country_id');
			$table->foreignId('bank_account_id')->nullable();
			$table->string('salutation');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('email')->unique();
			$table->string('phone');
			$table->string('street');
			$table->string('postcode');
			$table->string('city');
			$table->date('birthday');
			$table->dateTime('activated_at')->nullable();
			$table->boolean('wants_email_notification')->default(false);
			$table->timestamp('email_verified_at')->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->softDeletes();
			$table->timestamps();

			$table->foreign('level_id')->references('id')->on('levels');
			$table->foreign('country_id')->references('id')->on('countries');
			$table->foreign('bank_account_id')->references('id')->on('bank_accounts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('users');
	}
}
