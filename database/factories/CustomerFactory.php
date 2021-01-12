<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Faker\Provider\es_ES\Address; 

$factory->define(App\Models\Customer::class, function (Faker $faker) {
    return [
        'cif' => $faker->vat,
        'fiscal_name' => $faker->company,
        'comercial_name' => $faker->company . $faker->companySuffix,
        'email' => $faker->unique()->email,
        'phone' => $faker->phoneNumber,
        'street' => $faker->streetAddress,
        'town' => '',
        'city' => $faker->city,
        'country' => $faker->country,
        'postcode' => $faker->postcode,
        'shop' => $faker->company,
        'is_active' => $faker->boolean(90)
    ];
});