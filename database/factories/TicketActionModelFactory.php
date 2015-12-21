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
        'type' => $type,
        'body' => $faker->paragraph($faker->numberBetween(1, 4)),
        'transfer_id' => $type == 'transfer' ? $faker->numberBetween(1, 200) : null,
        'assigned_id' => $type == 'assign' ? $faker->numberBetween(1, 200) : null,
        'status' => $faker->randomElement(['open', 'closed', 'new', 'resolved']),
        'hours' => $faker->randomFloat(2, 0, 10),
        'created_at' => $date = $faker->dateTimeThisDecade(),
        'title' => $faker->sentence($faker->numberBetween(4, 10)),
        'priority' => $faker->numberBetween(1, 5),
        'source' => $faker->randomElement(['ui', 'mail', 'import'])
    ];
});
