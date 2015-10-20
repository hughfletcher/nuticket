<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'id' =>$faker->unique()->randomNumber(4),
        'display_name' => $faker->name,
        'is_staff' => 0
    ];
});

$factory->defineAs(App\User::class, 'staff', function ($faker) use ($factory) {
    $user = $factory->raw(App\User::class);

    return array_merge($user, ['is_staff' => 1]);
});
