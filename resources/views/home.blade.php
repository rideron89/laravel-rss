@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    Dashboard

                    <form class="pull-right" method="post" action="{{ url('/api/post/load') }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}" />

                        <button class="btn btn-sm btn-primary" type="submit">Refresh</button>
                    </form>

                    <div style="height: 5px;"></div>
                </div>

                <div class="panel-body">
                    <form method="get" action="{{ url('/home') }}">
                        <div class="input-group">
                            <input class="form-control" type="text" name="search" id="search" value="{{ app('request')->input('search') }}" />

                            <span class="input-group-btn">
                                <button class="btn btn-default" type="submit">Search</button>
                            </span>
                        </div>
                    </form>


                    @if (count($posts) < 1)
                        <div style="height: 25px;"></div>

                        <p>No posts were found.</p>
                    @else
                        <div style="height: 10px;"></div>

                        <ul class="list-group">
                            @foreach ($posts as $post)
                                <li class="list-group-item">
                                    <h4><a href="{{ $post->url }}" target="_blank">{{ $post->title }}</a></h4>

                                    <p>{{ $post->description }}</p>

                                    <p>Published on: {{ $post->date_published }}{{ $post->read ? '(read)' : '' }}</p>
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
@endsection
