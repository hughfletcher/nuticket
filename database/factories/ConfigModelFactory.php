<?php

$factory->define(App\Config::class, function (Faker\Generator $faker) {
    return [
        ['key' => 'system.eyes', 'value' => 'blue', 'enviroment' => 'production', 'id' => 1],
        ['key' => 'system.hair', 'value' => 'brunette', 'enviroment' => 'production', 'id' => 2],
        ['key' => 'system.hottie', 'value' => 0, 'enviroment' => 'production', 'id' => 3]
    ];
});
