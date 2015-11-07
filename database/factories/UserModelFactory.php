<?php

$factory->define(App\User::class, function (Faker\Generator $faker) {
    $first_name = $faker->firstName;
    $last_name = $faker->lastName;
    return [
        'username' => $faker->userName,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
        'email' => $faker->email,
        'is_admin' => 0,
        'is_staff' => 0
    ];
});

$factory->defineAs(App\User::class, 'testing', function ($faker) use ($factory) {

    return array_merge(
        $factory->raw(App\User::class),
        [
            'id' =>$faker->unique()->randomNumber(4),
            'updated_at' => ($date = $faker->dateTimeThisDecade()),
            'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
        ]
    );
});

// $factory->defineAs(App\User::class, 'staff', function ($faker) use ($factory) {
//     $user = $factory->raw(App\User::class);
//
//     return array_merge($user, ['is_staff' => 1]);
// });
