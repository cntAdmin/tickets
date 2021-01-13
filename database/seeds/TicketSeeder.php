<?php

use Illuminate\Database\Seeder;

class TicketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Ticket::class, 50)->create()
            ->each(function(\App\Models\Ticket $ticket){
                $ticket->comments()->save(factory(\App\Models\Comment::class)->make());
            });
    }
}
