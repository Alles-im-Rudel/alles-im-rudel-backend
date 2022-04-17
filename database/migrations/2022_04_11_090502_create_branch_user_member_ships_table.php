<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchUserMemberShipsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up(): void
	{
		Schema::create('branch_user_member_ships', function (Blueprint $table) {
			$table->id();
			$table->foreignId('user_id');
			$table->foreignId('branch_id');
			$table->dateTime('activated_at')->nullable();
			$table->dateTime('wants_to_leave_at')->nullable();
			$table->dateTime('exported_at')->nullable();
			$table->softDeletes();
			$table->timestamps();

			$table->foreign('branch_id')->references('id')->on('branches');
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
		Schema::dropIfExists('branch_user_member_ships');
	}
}
