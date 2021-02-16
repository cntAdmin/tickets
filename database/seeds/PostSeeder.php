<?php

use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Post::class, 50)->create()
            ->each(function(\App\Models\Post $post) {
                $post->attachments()->save(
                    \App\Models\Attachment::inRandomOrder()->first()
                );
                $post->user()->associate(\App\Models\User::inRandomOrder()->first());
                $post->save();
        });
    }
}
