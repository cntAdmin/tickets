<?php

namespace App\Models\Mtcdr;

use Illuminate\Database\Eloquent\Model;

class Call extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'ast_cdrs';

    public function ticket()
    {
        return $this->hasOne(\App\Models\Ticket::class, 'call_id', 'id');
    }
}
