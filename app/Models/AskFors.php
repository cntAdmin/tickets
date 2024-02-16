<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AskFors extends Model
{
    protected $fillable = ['name'];

    public function ask_for(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Ticket::class, 'ask_for_id', 'id');
    }
}
