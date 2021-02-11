<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Department;
use App\Models\Ticket;
use App\Models\TicketStatus;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Fakecar($faker));
    $user = User::find(rand(1,100));
    $dep = Department::find(rand(1,5));

    return [
        'custom_id' => $dep->code . '-' . $faker->randomNumber(8, true),
        'customer_id' => $user->customer_id,
        'user_id' => $user->id,
        'department_id' => $dep->id,
        'frame_id' => $faker->vin,
        'plate' => $faker->vehicleRegistration('[A-Z]{3}-[0-9]{4}'),
        'engine_type' => Str::random(5) . Str::random(5),
        'subject' => $faker->sentence(),
        'description' => $faker->randomHtml(2,2),
        'tests_done' => $faker->randomHtml(2,2),
        'ask_for' => 'asistencia',
        'knowledge_base' => $faker->boolean(50),
        'ticket_status_id' => TicketStatus::find(rand(1,3))->id,
    ];
});
