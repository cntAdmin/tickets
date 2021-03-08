<?php

use App\Imports\CustomersImport;
use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
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

        Customer::all()->each(function($c, $idx) {
            $user = App\Models\User::create([
                'name' => $c->comercial_name,
                'username' => 'cliente_' . str_pad($idx + 1, '5', '0', STR_PAD_LEFT),
                'phone' => $c->phone_1,
                'email' => $c->email,
                'password' => Hash::make(Str::random(16)),
                'is_active' => 1,
            ]);
            $user->customer()->associate($c);
            $user->save();
        });

    }
}
