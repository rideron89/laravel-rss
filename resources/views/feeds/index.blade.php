@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form method="post" action="/api/feed">
                        <input type="hidden" name="user_id" id="user_id" value="{{ Auth::user()->id }}" />

                        <h4>Add A New Field</h4>

                        <div style="height: 10px;"></div>

                        <div class="row">
                            <div class="col-md-4">
                                <label for="label">Label:</label>

                                <input type="text" class="form-control" name="label" id="label" />
                            </div>

                            <div class="col-md-8">
                                <label for="url">URL:</label>

                                <input class="form-control" type="text" name="url" id="url" />
                            </div>
                        </div>

                        <div style="height: 15px;"></div>

                        <fieldset>
                            <button class="btn btn-success" tyoe="submit">Add Feed</button>
                        </fieldset>
                    </form>

                    <hr />

                    <h3>Feeds:</h3>

                    <ul class="list-group">
                        @foreach ($feeds as $feed)
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-4">
                                        <form method="post" action="{{ url('/api/feed', $feed->id) }}">
                                            <input type="hidden" name="_method" value="PUT">

                                            <input class="form-control" type="text" name="label" value="{{ $feed->label }}" />

                                            <div style="height: 10px;"></div>

                                            <button class="btn btn-default" type="submit">Update</button>
                                        </form>
                                    </div>

                                    <div class="col-md-8">
                                        <form method="post" action="{{ url('/api/feed', $feed->id) }}">
                                            <input type="hidden" name="_method" value="DELETE">

                                            <input class="form-control" type="text" disabled="disabled" value="{{ $feed->url }}" />

                                            <div style="height: 10px;"></div>

                                            <button class="btn btn-danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
