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
            ->each(function(\App\Models\Ticket $ticket) {
                $brand = \App\Models\Brand::inRandomOrder()->first();
                $model = $brand->models->first();
                $status = \App\Models\TicketStatus::inRandomOrder()->first();
                $customer = \App\Models\Customer::inRandomOrder()->first();
                $ticket->update([
                    'custom_id' => $ticket->department->code . now()->year . '-' . str_pad($ticket->id, 5, '0', STR_PAD_LEFT)
                ]);
        
                $ticket->brand()->associate($brand);
                $ticket->car_model()->associate($model);
                $ticket->status()->associate($status);
                $ticket->customer()->associate($customer);
                $ticket->user()->associate($customer);
                $ticket->comments()->save(factory(\App\Models\Comment::class)->make());
                $ticket->save();
            });
    }
}
