@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form method="post" action="/api/feed">
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}" />

                        <fieldset>
                            <label for="url">Enter Feed URL:</label>

                            <div class="input-group">
                                <input class="form-control" type="text" name="url" id="url" />

                                <span class="input-group-btn">
                                    <button class="btn btn-success" type="submit">Add</button>
                                </span>
                            </div>
                        </fieldset>
                    </form>

                    <hr />

                    <h3>Feeds:</h3>

                    <ul class="list-group">
                        @foreach ($feeds as $feed)
                            <li class="list-group-item">{{ $feed->url }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
