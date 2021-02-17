<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Post;
use Faker\Generator as Faker;

$factory->define(Post::class, function (Faker $faker) {
    $html = '<h2>' . $faker->words(3, true) . '</h2><p>' . $faker->paragraphs(3, true) . '</p>';
    return [
        'title' => $faker->text(100),
        'description' => $html,
        'likes' => rand(1,100),
        'dislikes' => rand(1,100),
        'featured' => rand(0,1),
        'created_at' => now(),
        'updated_at' => now(),
    ];
});
