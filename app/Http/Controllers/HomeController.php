<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Post;
use App\UserFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
    * Show the application dashboard.
    *
    * @param Illuminate\Http\Request $request
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $userFeeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        // load posts based on query arguments
        $posts = Post::whereIn('feed_id', $userFeeds->pluck('feed.id'))
            ->notReadByUser(Auth::user())
            ->containsSearch($request->input('search'))
            ->belongsToFeed($request->input('feed'))
            ->setOrderBy($request->input('orderBy'))
            ->simplePaginate(15);

        // count the unread posts based on query arguments
        $unreadCount = Post::belongsToFeed($request->input('feed'))
            ->notReadByUser(Auth::user())
            ->containsSearch($request->input('search'))
            ->count();

        foreach ($posts as $post) {
            // include a shortened version of the post URL
            $post['shortUrl'] = parse_url($post->url, PHP_URL_HOST);
        }

        return view('home', compact('userFeeds', 'posts', 'unreadCount'));
    }

    /**
    * Return the user_feeds for a given user.
    *
    * @param Illuminate\Http\Request $request
    *
    * @return \Illuminate\Http\Response
    */
    public function feeds(Request $request)
    {
        $user_feeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        return view('feeds.index', compact('user_feeds'));
    }
}
