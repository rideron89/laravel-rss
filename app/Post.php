<?php

namespace App;

use App\Feed;
use App\User;
use App\UserPost;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'feed_id', 'title', 'description', 'url', 'date_published',
    ];

    protected $dateFormat = 'U';

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    /**
    * Scope a query to only include posts related to a given feed.
    *
    * @param Illuminate\Database\Eloquent\Builder $query
    * @param int $feed_id
    *
    * @return Illuminate\Database\Eloquent\Builder
    */
    public function scopeBelongsToFeed(Builder $query, $feed_id)
    {
        if ($feed_id) {
            $query = $query->where('feed_id', '=', $feed_id);
        }

        return $query;
    }

    /**
    * Scope a query to only include posts that contain the search terms in either the title
    * or description.
    *
    * @param Illuminate\Database\Eloquent\Builder $query
    * @param string $search
    *
    * @return Illuminate\Database\Eloquent\Builder
    */
    public function scopeContainsSearch(Builder $query, $search)
    {
        if ($search) {
            $query = $query->where('title', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        }

        return $query;
    }

    /**
    * Scope a query to only include posts that haven't been marked as 'read' by a given user.
    *
    * @param Illuminate\Database\Eloquent\Builder $query
    * @param App\User $user
    *
    * @return Illuminate\Database\Eloquent\Builder
    */
    public function scopeNotReadByUser(Builder $query, User $user)
    {
        $userPosts = UserPost::where(['read' => 1, 'user_id' => $user->id])->get();

        return $query->whereNotIn('id', $userPosts->pluck('post_id'));
    }

    /**
    * Scope a query to dynamically order results based on colon (:) separated input.
    *
    * @param Illuminate\Database\Eloquent\Builder $query
    * @param string $orderBy
    *
    * @return Illuminate\Database\Eloquent\Builder
    */
    public function scopeSetOrderBy(Builder $query, $orderBy)
    {
        if ($orderBy && strpos($orderBy, ':') !== false) {
            $orderBy = explode(':', $orderBy);

            $query = $query->orderBy($orderBy[0], $orderBy[1]);
        } else {
            $query = $query->orderBy('date_published', 'desc');
        }

        return $query;
    }
}
