<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDeptsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('depts', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('config_id');
			$table->string('name');
			$table->text('description')->nullable();
			$table->boolean('status');
			$table->integer('lft');
			$table->integer('rgt');
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
		Schema::drop('depts');
	}

}
