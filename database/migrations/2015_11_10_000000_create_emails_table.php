<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->increments('id');
            $table->boolean('autoresp')->default(true);
            $table->smallInteger('priority')->default(3);
            $table->string('email')->default('');
            $table->string('name')->default('');
            $table->string('userid')->default('');
            $table->string('userpass')->default('');
            $table->boolean('mail_active')->default(false);
            $table->string('mail_host');
            $table->enum('mail_protocol', ['pop', 'imap'])->default('pop');
            $table->boolean('mail_ssl')->default(true);
            $table->integer('mail_port')->nullable()->default(null);
            $table->tinyInteger('mail_fetchfreq')->default(5);
            $table->tinyInteger('mail_fetchmax')->default(30);
            $table->string('mail_archivefolder')->nullable()->default(null);
            $table->boolean('mail_delete')->default(false);
            $table->tinyInteger('mail_errors')->default(0);
            $table->timestamp('mail_lasterror')->nullable()->default(null);
            $table->timestamp('mail_lastfetch')->nullable()->default(null);
            $table->boolean('smtp_active')->default(false);
            $table->string('smtp_host');
            $table->integer('smtp_port');
            $table->boolean('smtp_secure')->default(true);
            $table->boolean('smtp_auth')->default(true);
            $table->timestamps();
            $table->unique('email');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('emails');
    }
}
