<?php

use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CustomersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new CustomersImport, 'imports/customers.csv');

        Customer::all()->each(function($customer) {
            $customer->users()->save(factory(\App\Models\User::class)->make());
        });

    }
}
