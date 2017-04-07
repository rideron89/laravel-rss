<?php

namespace App;

use App\Feed;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'feed_id', 'title', 'description', 'url', 'date_published',
    ];

    public function feed()
    {
        return $this->belongsTo(Feed::class);
    }
}
