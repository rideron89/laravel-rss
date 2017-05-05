<?php

namespace App;

use App\UserFeed;
use App\UserPost;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'real_name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function user_feed()
    {
        return $this->hasMany(UserFeed::class);
    }

    public function user_post()
    {
        return $this->hasMant(UserPost::class);
    }
}
