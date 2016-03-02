<?php

$factory->define(App\Config::class, function (Faker\Generator $faker) {
    return [
        ['key' => 'system.bool', 'value' => serialize($faker->boolean), 'id' => 1],
        ['key' => 'system.integer', 'value' => serialize($faker->randomNumber), 'id' => 2],
        ['key' => 'system.string', 'value' => serialize($faker->sentence), 'id' => 3],
        ['key' => 'system.array', 'value' => serialize($faker->words), 'id' => 4]
    ];
});
