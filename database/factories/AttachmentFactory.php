<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;

$factory->define(App\Models\Attachment::class, function (Faker $faker) {
$attachment_name = $faker->image(storage_path() . '/app/public/media', '500', '500', 'cats', false);

    return [
        'name' => $attachment_name,
        'path' => 'media/' . $attachment_name,
    ];
});
