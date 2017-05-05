<?php

namespace App;

use App\Feed;
use App\User;
use Illuminate\Database\Eloquent\Model;

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
}
