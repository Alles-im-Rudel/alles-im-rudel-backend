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
		Schema::create('members', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->dateTime('activated_at');
			// Felder die wir brauchen fürs anmelden Bankdaten etc
			$table->float('price');
			$table->boolean('is_allowed_mail');
			$table->boolean('is_allowed_phone');
			$table->timestamps();

			$table->foreign('user_id')->references('id')->on('users');
		});

		Schema::create('branches', function (Blueprint $table) {
			$table->id();
			$table->string('name');
			$table->float('price');
			$table->dateTime('activated_at');
			$table->timestamps();
		});

		Schema::create('branche_member', function (Blueprint $table) {
			$table->id();
			$table->foreignId('branch_id');
			$table->foreignId('member_id');
			$table->timestamps();

			$table->foreign('branch_id')->references('id')->on('branches');
			$table->foreign('member_id')->references('id')->on('members');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down(): void
	{
		Schema::dropIfExists('branche_member');
		Schema::dropIfExists('branches');
		Schema::dropIfExists('members');
	}
}
