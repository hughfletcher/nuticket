<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tickets', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id');
			$table->integer('dept_id');
			$table->integer('assigned_id')->default(0);
			$table->enum('status', array('new','open','closed','resolved'))->default('new');
			$table->smallInteger('priority')->default(3);
			$table->dateTime('last_action_at')->nullable();
			$table->decimal('hours', 4, 2)->default(0);
			$table->dateTime('closed_at')->nullable();
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
		Schema::drop('tickets');
	}

}
