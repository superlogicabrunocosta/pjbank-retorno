<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/**
 * Class CreateRecurrenceChargesTable.
 */
class CreateRecurrenceChargesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('recurrence_charges', function(Blueprint $table) {
			$table->id('id');
			$table->foreignId('recurrence_id')->constrained('recurrences');
			$table->unsignedBigInteger('charge_id')->nullable();
			$table->unsignedDouble('value_recurrence');
			$table->unsignedDouble('value_charge');
			$table->date('date_recurrence');
			$table->date('date_due');
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
		Schema::drop('recurrence_charges');
	}
}
