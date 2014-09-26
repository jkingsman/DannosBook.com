<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateBookeesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bookees', function(Blueprint $table) {
			$table->increments('id');
			$table->string('jail_id', 63)->nullable();
			$table->string('name', 255);
			$table->datetime('bookingdate')->nullable();
			$table->string('address', 511)->nullable();
			$table->datetime('latestchargedate')->nullable();
			$table->string('gender', 31)->nullable();
			$table->datetime('arrestdate')->nullable();
			$table->datetime('birthdate');
			$table->string('arrestagency', 128)->nullable();
			$table->string('occupation', 128)->nullable();
			$table->string('arrestlocation', 511)->nullable();
			$table->string('height', 8)->nullable();
			$table->string('haircolor', 31)->nullable();
			$table->integer('weight')->nullable();
			$table->string('eyecolor', 31)->nullable();
			$table->string('courtlink', 511)->nullable();
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
		Schema::drop('bookees');
	}

}
