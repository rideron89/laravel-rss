<?php

namespace App;

use App\Feed;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserFeed extends Model
{
    protected $fillable = [
        'title', 'user_id', 'feed_id',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
    * Scope a query to also include a count of all the posts for the UserFeed. The scope defaults
    * to showing only unread posts.
    *
    * @param Illuminate\Database\Eloquent\Builder $query
    * @param Boolean $unread_only (Default: true)
    *
    * @return Illuminate\Database\Eloquent\Builder
    */
    public function scopeGetPostCount(Builder $query, $unread_only = true)
    {
        return $query->leftJoin('posts', 'posts.feed_id', '=', 'user_feeds.feed_id')
            ->when($unread_only, function($query) {
                $userPosts = UserPost::where(['read' => 1, 'user_id' => Auth::user()->id])->get();

                return $query->whereNotIn('posts.id', $userPosts->pluck('post_id'));
            })
            ->groupBy('posts.feed_id')
            ->select('user_feeds.*', DB::raw('count(*) as post_count'));
    }
}
