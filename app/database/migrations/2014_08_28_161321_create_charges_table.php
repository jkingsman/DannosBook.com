<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateChargesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('charges', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('bookee_id');
			$table->string('charge', 63);
			$table->string('description', 127);
			$table->string('type', 15);
			$table->decimal('bail');
			$table->datetime('sentencetime');
			$table->string('auth', 127);
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
