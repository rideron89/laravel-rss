@extends('layouts.app')

@section('content')
<div class="container">
   <div class="row">
       <div class="col-md-8 col-md-offset-2">
           <a href="{{ url('/feeds') }}">Back</a>
       </div>
   </div>

   <br />

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <form action="delete" onsubmit="return false;">
                        <div class="input-group">
                            <input class="form-control" type="text" disabled="disabled" value="{{ $feed->url }}" />

                            <span class="input-group-btn">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
