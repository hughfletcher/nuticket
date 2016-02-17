<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
			$table->text('timezone', 65535)->nullable();
			$table->text('locale', 65535)->nullable();
			$table->string('username', 128)->unique('accounts_username_unique');
			$table->string('password', 128)->nullable();
			$table->string('first_name', 128)->nullable();
			$table->string('middle_name', 128)->nullable();
			$table->string('last_name', 128)->nullable();
			$table->string('display_name')->nullable();
			$table->string('email')->nullable();
			$table->integer('org_id')->default(null)->nullable();
			$table->boolean('is_staff')->default(0);
			$table->boolean('is_admin')->default(0);
			$table->string('source', 128)->default('local');
			$table->timestamps();
			$table->softDeletes();
			$table->rememberToken()->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}
