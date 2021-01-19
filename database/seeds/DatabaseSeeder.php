<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            PermissionsRolesSeeder::class,
            DepartmentsSeeder::class,
            CustomersSeeder::class,
            UsersSeeder::class,
            TicketStatusSeeder::class,
            TicketSeeder::class,
            AttachmentsSeeder::class,
            ]);
    }
}
