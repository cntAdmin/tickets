<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Customer;
use App\Models\Department;
use App\Models\EngineType;
use App\Models\Ticket;
use App\Models\User;
use Faker\Generator as Faker;

$factory->define(Ticket::class, function (Faker $faker) {
    $faker->addProvider(new \Faker\Provider\Fakecar($faker));

    return [
        'user_id' => User::find(rand(1,100)),
        'department_id' => Department::find(rand(1,5)),
        'frame_id' => $faker->vin,
        'plate' => $faker->vehicleRegistration('[A-Z]{3}-[0-9]{4}'),
        'brand' => $faker->vehicleBrand,
        'model' => $faker->vehicleModel,
        'engine_type_id' => EngineType::find(rand(1,3)),
        'subject' => $faker->sentence(),
        'description' => $faker->paragraph(),
        'tests_done' => $faker->paragraph(),
        'ask_for' => 'asistencia',
        'knowledge_base' => $faker->boolean(50),
        'status' => 'opened',
    ];
});
