<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTimeLogTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('time_logs', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->decimal('hours', 4, 2);
			$table->enum('type', ['action', 'other', 'sick', 'holiday', 'vacation']);
			$table->text('message');
			$table->integer('ticket_action_id')->nullable();
			$table->timestamp('time_at');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('time_logs');
	}

}
