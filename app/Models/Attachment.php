<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'path', 'deleted_by'
    ];
    
    public function tickets()
    {
        return $this->morphedByMany(\app\Models\Ticket::class, 'attachable');
    }
    public function comments()
    {
        return $this->morphedByMany(\app\Models\Comment::class, 'attachable');
    }
}
