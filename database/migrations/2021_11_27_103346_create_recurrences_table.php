<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRecurrencesTable.
 */
class CreateRecurrencesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recurrences', function(Blueprint $table) {
			$table->id('id');
			$table->foreignId('account_id')->constrained('accounts');
			$table->string('type');
			$table->string('bank_code', 3);
			$table->json('config');
			$table->string('filename')->nullable();
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
		Schema::drop('recurrences');
	}
}
