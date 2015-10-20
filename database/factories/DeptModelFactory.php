<?php

$factory->define(App\Dept::class, function (Faker\Generator $faker) {
    return [
        'id' =>$faker->unique()->randomNumber(4),
        'name' => $faker->company,
        'description' => ( $faker->boolean ? $faker->catchPhrase : null ),
        'status' => 1,
        'lft' => 1,
        'rgt' => 1,
        'updated_at' => ($date = $faker->dateTimeThisDecade()),
        'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
    ];
});
