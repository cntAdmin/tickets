<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachable extends Model
{
    public function posts()
    {
        return $this->morphedByMany(\App\Models\Post::class, 'attachable');
    }
    public function comments()
    {
        return $this->morphedByMany(\App\Models\Comment::class, 'attachable');
    }
    public function tickets()
    {
        return $this->morphedByMany(\App\Models\Ticket::class, 'attachable');
    }

}
