<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateConfigTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create(
			'config', function ($table) {
				$table->increments('id');
				$table->string('environment', 255);
				$table->string('key', 255)->index();
				$table->text('value');
				$table->unique(array('environment', 'key'));
			}
		);
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('config');
	}

}
