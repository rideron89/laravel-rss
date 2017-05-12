<?php

namespace App\Http\Controllers;

use App\Feed;
use App\UserFeed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserFeedsController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userFeeds = UserFeed::with('feed')->where('user_id', Auth::user()->id)->get();

        return response(json_encode(['status' => 'ok', 'items' => $userFeeds]));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feed = Feed::firstOrCreate(['url' => $request->input('url')]);

        $userFeed = UserFeed::create(array_merge(
            $request->all(),
            ['feed_id' => $feed->id, 'user_id' => Auth::user()->id]
        ));

        return response(json_encode(['status' => 'ok', 'items' => [$userFeed]]));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\UserFeed  $userFeed
     * @return \Illuminate\Http\Response
     */
    public function show(UserFeed $userFeed)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\UserFeed  $userFeed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, UserFeed $userFeed)
    {
        if (Auth::user()->id != $userFeed->user_id) {
            return response(json_encode(['status' => 'error', 'message' => 'Unauthorized to update this feed']));
        }

        $userFeed->update($request->all());

        return response(json_encode(['status' => 'ok', 'items' => [$userFeed]]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserFeed  $userFeed
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFeed $userFeed)
    {
        if (Auth::user()->id != $userFeed->user_id) {
            return response(json_encode(['status' => 'error', 'message' => 'Unauthorized to delete this feed']));
        }

        $userFeed->delete();

        return response(json_encode(['status' => 'ok']));
    }
}
