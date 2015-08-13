<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class InstallSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Model::unguard();

		App\Dept::create(['name' => 'Example Company', 'description' => 'Showing you how it work\'s', 'status' => 1, 'lft' => 1, 'rgt' => 2]);
	}

}
