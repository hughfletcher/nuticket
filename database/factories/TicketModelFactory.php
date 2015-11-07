<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/**
 * General Collection
 *
 * [user_id, dept_id, assigned_id, status, priority, hours, created_at, updated_at, last_action_at, closed_at]
 *
 * @return Illuminate\Database\Eloquent\Collection || App\Ticket
 */
$factory->define(App\Ticket::class, function (Faker\Generator $faker) {
    return [
        'user_id' => $faker->unique()->randomNumber(4),
        'assigned_id' => $faker->unique()->randomNumber(4),
        'dept_id' => $faker->unique()->randomNumber(4),
        'status' => $faker->randomElement(['open', 'closed', 'new', 'resolved']),
        'priority' => $faker->numberBetween(1, 5),
        'hours' => $faker->randomFloat(2, 0, 10),
        'updated_at' => ($date = $faker->dateTimeThisDecade()),
        'last_action_at' => $faker->dateTimeThisDecade($date),
        'created_at' => ( $faker->boolean ? $date : $faker->dateTimeThisDecade($date) )
    ];
});


// $factory->defineAs(App\Ticket::class, 'with_relations', function ($faker) use ($factory) {
//
//     $user = factory();
//     $assigned = factory();
//     $dept = factory();
//     $actions = factory();
//
//     return array_merge(
//         $factory->raw(App\Ticket::class),
//         [
//             'user_id' => $user->id,
//             'user' => $user,
//             'assigned_id' => $assigned->id,
//             'assigned' => $assigned,
//             'dept_id' => $dept->id,
//             'dept' => $dept,
//             'actions' => $actions
//         ]
//     );
// });

/**
 * Generated collection for use in submitting ticket_create
 *
 * [user_id,dept_id,assigned_id,status,priority,hours,display_name,email,title,body,time_at,reply_body,comment_body]
 *
 * @return App\Ticket
 */
// $factory->defineAs(App\Ticket::class, 'ticket_create', function ($faker) use ($factory) {
//     $ticket = $factory->raw(App\Ticket::class);
//     $except = ['created_at', 'updated_at', 'last_action_at', 'actions', 'closed_at'];
//     $create = [
//         'display_name' => $faker->name,
//         'email' => $faker->email,
//         'title' => $faker->sentence($faker->numberBetween(4, 10)),
//         'body' => $faker->paragraph($faker->numberBetween(1, 4)),
//         'time_at' => $faker->boolean(50) ? $faker->dateTimeThisMonth() : null,
//         'reply_body' => $faker->paragraph($faker->numberBetween(1, 4)),
//         'comment_body' => $faker->paragraph($faker->numberBetween(1, 4)),
//     ];
//
//     return array_except(array_merge($ticket, $create), $except);
// });
