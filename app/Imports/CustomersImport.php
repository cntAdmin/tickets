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
            'custom_id' => strtoupper($row['codigo']),
            'fiscal_name' => ucwords(strtolower($row['talleres'])),
            'comercial_name' => ucwords(strtolower($row['talleres'])),
            'email' => strtolower($row['email']),
            'province' => ucwords(strtolower($row['provincia'])),
            'phone_1' => $row['telefono'],
            'shop' => ucwords(strtolower($row['tienda'])),
        ]);
    }
}
