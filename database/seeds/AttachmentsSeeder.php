<?php

use Illuminate\Database\Seeder;

class AttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Attachment::class, 50)->create()
            ->each(function($attachment){
                $attachment->comments()->save(App\Models\Comment::find(rand(1,50)));
            });
    }
}
