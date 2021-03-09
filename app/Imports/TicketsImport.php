<?php

namespace App\Imports;

use App\Models\Customer;
use App\Models\Ticket;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TicketsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    */
    public function model(array $row)
    {
        $customer = Customer::where('custom_id', $row['cliente'])->first();
        $contact = $customer->contacts()->first();

        return new Ticket([
            'customer_id' => $customer->id,
            'user_id' => $contact->id,
            'department_id' => 1,
            'other_brand_model' => 'otro_modelo',
            'subject' => $row['asunto'],
            'ask_for' => $row['solicito'],
            'description' => $row['descripcion'],
            'knowledge_base' => 0
        ]);
    }
}
