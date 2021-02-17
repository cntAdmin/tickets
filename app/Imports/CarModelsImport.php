<?php

namespace App\Imports;

use App\Models\CarModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CarModelsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model
    */
    public function model(array $row)
    {
        return new CarModel([
            'brand_id' => $row['id'],
            'name' => $row['modelo'],
        ]);
    }
}
