<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Facades\File;

$factory->define(App\Models\Attachment::class, function (Faker $faker) {
    if(!file_exists('/public/storage/media')){
        File::makeDirectory('/public/storage/media', 0777, true, true);
    }

    $attachment_name = $faker->image('/public/storage/media', '500', '500', 'cats', false);

    return [
        'name' => $attachment_name,
        'path' => 'media/' . $attachment_name,
    ];
});
