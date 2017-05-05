<?php

namespace App\Http\Controllers;

use App\Feed;
use App\UserFeed;
use Illuminate\Http\Request;

class UserFeedsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feed = Feed::firstOrNew(['url' => $request->input('url')]);

        UserFeed::create(array_merge($request->all(), ['feed_id' => $feed->id]));

        return redirect()->back();
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
        $userFeed->update($request->all());

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\UserFeed  $userFeed
     * @return \Illuminate\Http\Response
     */
    public function destroy(UserFeed $userFeed)
    {
        $userFeed->delete();

        return redirect()->back();
    }
}
