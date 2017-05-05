<?php

namespace App;

use App\Post;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    protected $fillable = [
        'url',
    ];

    public function post()
    {
        return $this->hasMany(Post::class);
    }
}
