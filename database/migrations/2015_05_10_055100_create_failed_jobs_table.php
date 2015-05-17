<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFailedJobsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('failed_jobs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('connection', 65535);
			$table->text('queue', 65535);
			$table->text('payload', 65535);
			$table->dateTime('failed_at')->default('0000-00-00 00:00:00');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('failed_jobs');
	}

}
