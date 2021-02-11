<?php

use App\Imports\CarModelsImport;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class CarModelsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(new CarModelsImport, 'imports/car_models.csv');
    }
}
