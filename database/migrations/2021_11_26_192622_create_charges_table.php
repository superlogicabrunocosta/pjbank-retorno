<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateChargesTable.
 */
class CreateChargesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('charges', function (Blueprint $table) {
			$table->id('id');
			$table->uuid('uuid')->unique();
			$table->foreignId('account_id')->constrained('accounts');
			$table->unsignedDouble('value');
			$table->date('date_due');
			$table->tinyInteger('sync_our_number')->nullable();
			$table->string('sync_bank_code', 3)->nullable();
			$table->string('sync_status', 3)->nullable();
			$table->unsignedBigInteger('sync_id')->nullable();
			$table->string('sync_message')->nullable();
			$table->json('sync_data')->nullable();
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
		Schema::drop('charges');
	}
}
