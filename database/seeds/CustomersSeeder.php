<?php

use Illuminate\Database\Seeder;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(\App\Models\Customer::class, 100)->create()
            ->each(function ($customer) {
                $customer->contacts()->save(factory(App\Models\Contact::class)->make());
            });
    }
}
