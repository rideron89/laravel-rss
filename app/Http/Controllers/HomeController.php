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
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // filter incoming query arguments
        $feed   = $request->input('feed');
        $search = $request->input('search');

        $userFeeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        // load posts based on query arguments
        $posts = Post::whereIn('feed_id', $userFeeds->pluck('feed.id'))
            // ->where('read', '=', false)
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->when($feed, function($query) use ($feed) {
                // TODO: probably doesn't work as expected
                return $query->where('feed_id', '=', $feed);
            })
            ->orderBy('updated_at', 'DESC')
            ->simplePaginate(15);

        // count the unread posts based on query arguments
        $unreadCount = Post::when($feed, function($query) use ($feed) {
                // TODO: probably doesn't work as expected
                return $query->where('feed_id', '=', $feed);
            })
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->count();

        // include a shortened version of each post URL
        foreach ($posts as $post) {
            $host   = parse_url($post->url, PHP_URL_HOST);

            $post['shortUrl'] = $host;
        }

        return view('home', compact('userFeeds', 'posts', 'unreadCount'));
    }

    public function feeds()
    {
        $user_feeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        return view('feeds.index', compact('user_feeds'));
    }
}
