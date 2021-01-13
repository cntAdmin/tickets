<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Comment;
use Faker\Generator as Faker;
/**
 *  $table->foreignId('ticket_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
 *  $table->foreignId('user_id')->nullable()->constrained()->onUpdate('cascade')->onDelete('cascade');
 *  $table->text('description')->nullable();
 */
$factory->define(Comment::class, function (Faker $faker) {
    return [
        'user_id' => \App\Models\User::find(rand(1,100)),
        'description' => $faker->paragraphs(6, true),
    ];
});
