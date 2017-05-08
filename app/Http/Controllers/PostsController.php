<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Post;
use App\UserPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    /**
    * Add a user_post entry marking the post as 'read' by the user.
    *
    * @param \Illuminate\Http\Request $request
    * @param App\Post $post
    *
    * @return \Illuminate\Http\Response
    */
    public function markRead(Request $request, Post $post)
    {
        $userPost = UserPost::firstOrNew([
            'user_id' => Auth::user()->id,
            'post_id' => $post->id,
        ]);

        $userPost->read = 1;
        $userPost->read_at = time();

        $userPost->save();

        return response($userPost);
    }
}
