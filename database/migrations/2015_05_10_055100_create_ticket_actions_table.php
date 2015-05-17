<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTicketActionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ticket_actions', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('ticket_id');
			$table->integer('user_id');
			$table->enum('type', array('create','reply','comment','assign','closed','edit','transfer','open','resolved'));
			$table->decimal('time_spent', 4)->default(0.00);
			$table->integer('assigned_id')->nullable();
			$table->integer('transfer_id')->nullable();
			$table->string('title')->nullable();
			$table->text('body', 16777215);
			$table->timestamps();
			$table->softDeletes();
			$table->index(['ticket_id','user_id','assigned_id','transfer_id'], '`RELATION`');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('ticket_actions');
	}

}
