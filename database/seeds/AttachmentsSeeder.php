<?php

use App\Models\Ticket;
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
            ->each(function($attachment) {
                $attachment->tickets()->attach(Ticket::find(rand(1,50)));
            });
    }
}
