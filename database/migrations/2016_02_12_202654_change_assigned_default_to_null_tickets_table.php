<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeAssignedDefaultToNullTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `tickets` CHANGE COLUMN `assigned_id` `assigned_id` INT(11) NULL DEFAULT NULL AFTER `org_id`;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `tickets` CHANGE COLUMN `assigned_id` `assigned_id` INT(11) NOT NULL DEFAULT '0' AFTER `org_id`;");
    }
}
