<?php

$factory->define(App\TicketAction::class, function (Faker\Generator $faker) {
    $assigned_id = null;
    $transfer_id = null;
    $type = $faker->randomElement([
        'create','reply','comment','assign','closed','edit','transfer','open','resolved'
    ]);
    return [
        'ticket_id' => $faker->numberBetween(1, 200),
        'user_id' => $faker->numberBetween(1, 200),
        'created_at' => $date = $faker->dateTimeThisDecade(),
        'title' => $faker->sentence($faker->numberBetween(4, 10)),
        'body' => $faker->paragraph($faker->numberBetween(1, 4)),
        'type' => $type,
        'assigned_id' => $type == 'assign' ? : $assigned_id,
        'priority' => $faker->numberBetween(1, 5),
        'status' => $faker->randomElement(['open', 'closed', 'new', 'resolved']),
        'hours' => $faker->randomFloat(2, 0, 10),
    ];
});
