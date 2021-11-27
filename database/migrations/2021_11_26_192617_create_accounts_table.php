<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateAccountsTable.
 */
class CreateAccountsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('accounts', function(Blueprint $table) {
			$table->id('id');
			$table->string('enviroment');
			$table->string('name');
			$table->string('cnpj');
			$table->string('bank_code');
			$table->string('bank_agency');
			$table->string('bank_account');
			$table->string('credential', 40);
			$table->string('secret', 40);
			$table->string('webhook', 40);
            $table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('accounts');
	}
}
