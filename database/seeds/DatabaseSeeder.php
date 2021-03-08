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
            CustomersSeeder::class,
            UsersSeeder::class,
            BrandSeeder::class,
            CarModelsSeeder::class,
            TicketStatusSeeder::class,
            // DepartmentsSeeder::class,
            // TicketSeeder::class,
            // AttachmentsSeeder::class,
            // PostSeeder::class,
            ]);
    }
}
