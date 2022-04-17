<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
	{
        Schema::create('bank_accounts', function (Blueprint $table) {
			$table->id();
			$table->foreignId('country_id');
			$table->string('iban');
			$table->string('bic');
			$table->string('first_name');
			$table->string('last_name');
			$table->string('street');
			$table->string('postcode');
			$table->string('city');
			$table->string('signature_city');
			$table->timestamps();

			$table->foreign('country_id')->references('id')->on('countries');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
	{
        Schema::dropIfExists('bank_accounts');
    }
}
