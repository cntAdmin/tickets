<?php

use App\Imports\BrandsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new BrandsImport, 'imports/brands.csv');
    }
}
