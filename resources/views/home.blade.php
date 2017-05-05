@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Dashboard <strong>({{ $unreadCount }} unread posts)</strong>

                    <form class="pull-right" method="post" action="{{ url('/api/post/load') }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

                        <button class="btn btn-sm btn-primary" type="submit">Refresh</button>
                    </form>

                    <div style="height: 5px;"></div>
                </div>

                <div class="panel-body">
                    <form method="get" action="{{ url('/') }}">
                        <div class="input-group">
                            <input class="form-control" type="text" name="search" id="search" value="{{ app('request')->input('search') }}" />

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Search</button>
                            </span>
                        </div>
                    </form>

                    <div style="height: 10px;"></div>

                    <div class="row">
                        <div class="col-md-2">
                            <h4>Filter by Feed</h4>

                            <ul class="list-group">
                                @foreach ($userFeeds as $feed)
                                    <a href="{{ url('/') }}?feed={{ $feed->id }}" class="list-group-item">
                                        {{ $feed->title }}
                                    </a>
                                @endforeach
                            </ul>
                        </div>

                        <div class="col-md-10">
                            @if (count($posts) < 1)
                                <div style="height: 25px;"></div>

                                <p>No posts were found.</p>
                            @else
                                <ul class="list-group">
                                    @foreach ($posts as $post)
                                        <li class="list-group-item">
                                            <h4><home-post-link
                                                post-id="{{ $post->id }}"
                                                href="{{ $post->url }}"
                                                target="_blank"
                                                text="{{ $post->title }}"></home-post-link></h4>

                                            <p>{{ $post->description }}</p>

                                            <p><em>Published on: {{ date('d-m-Y H:m:s', $post->date_published) }} ({{ $post->shortUrl }})</em></p>
                                        </li>
                                    @endforeach
                                </ul>

                                <div style="height: 10px;"></div>

                                <div class="text-center">{{ $posts->links() }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
