<?php

namespace App\Http\Controllers;

use App\Feed;
use App\Post;
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
        $search = $request->input('search');

        $feeds = Feed::where('user_id', Auth::user()->id)->get(['id'])->pluck('id');
        $posts = Post::whereIn('feed_id', $feeds)
            ->when($search, function($query) use ($search) {
                return $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            })
            ->orderBy('date_published', 'DESC')
            ->simplePaginate(15);

        return view('home', compact('posts'));
    }

    public function feeds()
    {
        $feeds = Feed::where(['user_id' => Auth::user()->id])->get();

        return view('feeds.index', compact('feeds'));
    }
}
