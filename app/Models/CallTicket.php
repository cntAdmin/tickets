<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CallTicket extends Pivot
{
    protected $connection = 'mysql';
}
