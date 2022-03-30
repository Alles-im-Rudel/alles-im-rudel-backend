<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMembersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('countries', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('iso_code');
		});

		Schema::create('member_ships', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->foreignId('country_id');
			$table->string('salutation');
			$table->string('phone');
			$table->string('street');
			$table->string('postcode');
			$table->string('city');
			$table->string('iban');
			$table->dateTime('activated_at')->nullable();
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('country_id')->references('id')->on('countries');
		});

		Schema::create('branches', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->string('description');
			$table->float('price');
			$table->dateTime('activated_at');
			$table->boolean('is_selectable');
			$table->timestamps();
		});

		Schema::create('branch_member_ship', function (Blueprint $table) {
			$table->id();
			$table->foreignId('branch_id');
			$table->foreignId('member_ship_id');

			$table->foreign('branch_id')->references('id')->on('branches');
			$table->foreign('member_ship_id')->references('id')->on('member_ships');
		});

		Schema::create('contact_types', function (Blueprint $table) {
			$table->id();
			$table->string('name');
		});

		Schema::create('contact_type_user', function (Blueprint $table) {
			$table->id();
			$table->foreignId('contact_type_id');
			$table->foreignId('user_id');
			$table->timestamps();

			$table->foreign('contact_type_id')->references('id')->on('contact_types');
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
		Schema::dropIfExists('contact_type_user');
		Schema::dropIfExists('contact_types');
		Schema::dropIfExists('branch_member_ship');
		Schema::dropIfExists('branches');
		Schema::dropIfExists('member_ships');
		Schema::dropIfExists('countries');
	}
}
