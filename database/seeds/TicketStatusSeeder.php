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
                'name' => 'Nuevo',
                'menu_name' => 'Nuevas',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Abierto',
                'menu_name' => 'Abiertas',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Cerrado',
                'menu_name' => 'Cerradas',
                'created_at' => now(),
                'updated_at' => now()
            ],[
                'name' => 'Resuelto',
                'menu_name' => 'Resueltas',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        \App\Models\TicketStatus::insert($types);
    }
}
