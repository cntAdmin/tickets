<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'title', 'description', 'likes', 'dislikes', 'published', 'featured'
    ];
    
    protected $appends = ['short_description'];

    public function getShortDescriptionAttribute() {
        return Str::substr($this->attributes['description'], 0, 50);
    }

    /**
     * Get the attachments that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */

    public function attachments(): MorphToMany
    {
        return $this->morphToMany(\App\Models\Attachment::class, 'attachable');
    }
    /**
     * Get the user that owns the Post
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
