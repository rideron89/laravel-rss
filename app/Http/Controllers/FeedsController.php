<?php

namespace App\Http\Controllers;

use App\Feed;
use Illuminate\Http\Request;

class FeedsController extends Controller
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
        $feeds = Feed::all();

        return $feeds;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $feed = Feed::create($request->all());

        return response(json_encode(['status' => 'ok', 'item' => $feed]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Feed $feed)
    {
        $feed->update($request->all());

        return response(json_encode(['status' => 'ok', 'item' => $feed]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Feed  $feed
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Feed $feed)
    {
        $feed->delete();

        return response(json_encode(['status' => 'ok']));
    }
}
