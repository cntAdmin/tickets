<?php

namespace App\Imports;

use App\Models\Customer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CustomersImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Customer([
            'custom_id' => strtoupper($row['custom_id']),
            'fiscal_name' => ucwords(strtolower($row['comercial_name'])),
            'comercial_name' => ucwords(strtolower($row['comercial_name'])),
            'email' => strtolower($row['email']),
            'province' => ucwords(strtolower($row['province'])),
            'phone_1' => $row['phone_1'],
            'shop' => ucwords(strtolower($row['shop'])),
        ]);
    }
}
