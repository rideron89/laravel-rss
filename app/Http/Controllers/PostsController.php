<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Post;
use App\UserFeed;
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

    public function index(Request $request)
    {
        $userFeeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();
        $posts = Post::with('feed.user_feed')
            ->whereIn('feed_id', $userFeeds->pluck('feed.id'))
            ->notReadByUser(Auth::user())
            ->containsSearch($request->input('search'))
            ->belongsToFeed($request->input('feed'))
            ->setOrderBy($request->input('orderBy'))
            ->simplePaginate(15);

        foreach ($posts as $post) {
            $post->date_published = date('d-m-Y H:m:s', $post->date_published);

            $post['short_url'] = parse_url($post->url, PHP_URL_HOST);
        }

        return response(['status' => 'ok', 'items' => $posts]);
    }

    public function bulkMarkRead(Request $request)
    {
        $time = $request->input('time');
        $difference = time() - $time;

        $userFeeds = UserFeed::where('user_id', Auth::user()->id)->get();
        $userPosts = UserPost::where('user_id', Auth::user()->id)->get();

        $parsedPosts = [];
        $posts = Post::where('date_published', '<', $difference)
            ->whereIn('feed_id', $userFeeds->pluck('id'))
            ->whereNotIn('id', $userPosts->pluck('post_id'))
            ->get();

        foreach ($posts as $post) {
            $parsedPosts[] = [
                'user_id' => Auth::user()->id,
                'post_id' => $post->id,
                'created_at' => time(),
                'updated_at' => time(),
                'read' => '1',
                'read_at' => time(),
            ];
        }

        UserPost::insert($parsedPosts);

        return response(count($parsedPosts));
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
