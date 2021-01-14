<?php

use Illuminate\Database\Seeder;

class TicketStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            [
                'name' => 'Abierto',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Cerrado',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Resuelto',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        \App\Models\TicketStatus::insert($types);
    }
}
