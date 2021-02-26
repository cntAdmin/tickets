<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Post extends Model
{
    protected $fillable = [
        'image', 'title', 'description', 'likes', 'dislikes', 'published', 'featured'
    ];
    
    protected $with = ['created_by'];

    protected $appends = ['short_description'];

    public function getShortDescriptionAttribute() {
        return Str::substr($this->attributes['description'], 0, 50);
    }

    public function scopeFilterPosts(Builder $query)
    {
        return $query->when(request()->input('text'), function(Builder $q, $text) {
                $q->where('title', 'LIKE', '%' . $text. '%')->orWhere('description', 'LIKE', '%' . $text . '%');
            })->when(request()->input('published'), function(Builder $q, $published) {
                switch ($published) {
                    case '1':
                        $q->where('published', 1);
                        break;
                    
                    default:
                        $q->where('published', 0);
                    break;
                }
            })->when(request()->input('type'), function(Builder $q, $type) {
                switch ($type) {
                    case 'featured':
                        return $q->whereFeatured(true);
                        break;
                    default:
                        return $q;
                        break;
                }
            })->when(request()->input('date_from'), function(Builder $q, $date_from) {
                $q->whereDate('created_at', '>=', $date_from);
            })->when(request()->input('date_to'), function(Builder $q, $date_to) {
                $q->whereDate('created_at',  '<=', $date_to);
            });
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
    public function created_by(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id');
    }
}
