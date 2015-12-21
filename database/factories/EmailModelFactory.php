<?php

use Carbon\Carbon;

$factory->define(App\Email::class, function (Faker\Generator $faker) {
    return [
        'id' =>$faker->unique()->randomNumber(4),
        'autoresp' => $faker->boolean,
        'priority' => $faker->numberBetween(1, 5),
        'email' => $faker->email,
        'name' => $faker->company,
        'userid' => $faker->userName,
        'userpass' => $faker->password,
        'mail_active' => true,
        'mail_host' => 'mail.' . $faker->domainName,
        'mail_protocol' => $faker->randomElement(['pop', 'imap']),
        'mail_ssl' => $faker->boolean,
        'mail_port' => $faker->randomNumber(3),
        'mail_fetchfreq' => $faker->randomNumber(2),
        'mail_fetchmax' => $faker->randomNumber(2),
        'mail_archivefolder' => 'tickets',
        'mail_delete' => $faker->boolean,
        'mail_errors' => null,
        'mail_lasterror' => null,
        'mail_lastfetch' => $faker->dateTimeThisMonth(),
        'smtp_active' => true,
        'smtp_host' => 'smtp.' . $faker->domainName,
        'smtp_port' => $faker->randomNumber(3),
        'smtp_secure' => $faker->boolean,
        'smtp_auth' => $faker->boolean,
        'updated_at' => $updated = $faker->dateTimeThisYear(),
        'created_at' => $faker->dateTimeThisYear($updated),
    ];
});
