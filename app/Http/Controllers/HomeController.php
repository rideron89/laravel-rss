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
    * DEPRECATED
    *
    * Show the application dashboard.
    *
    * @param Illuminate\Http\Request $request
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        $userFeeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        $query = Post::whereIn('feed_id', $userFeeds->pluck('feed.id'))
            ->notReadByUser(Auth::user())
            ->containsSearch($request->input('search'))
            ->belongsToFeed($request->input('feed'));

        $posts = $query->setOrderBy($request->input('orderBy'))->simplePaginate(15);

        foreach ($posts as $post) {
            $post['shortUrl'] = parse_url($post->url, PHP_URL_HOST);
        }

        return view('home', compact('userFeeds', 'posts'));
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
