<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateLaqAsyncQueueTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('laq_async_queue', function(Blueprint $table) {
			$table->increments('id');
			$table->integer('status');
			$table->integer('retries');
			$table->integer('delay');
			$table->text('payload')->nullable();
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
		Schema::drop('laq_async_queue');
	}

}
