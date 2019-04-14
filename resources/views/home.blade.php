@extends('layouts.app')

@section('content')


<div class="container">
    {{$errors->first('error')}}
        <div class="row">
            <form action="{{route('url.create')}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="url">Your url</label>
                    <input type="url" id="url" class="form-control{{ $errors->has('url') ? ' is-invalid' : '' }}" placeholder="Url" name="url">
                    @if ($errors->has('url'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('url') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    @if($errors->has('error'))
                        <h4>{{$errors->first('error')}}</h4>
                    @endif
                    <label for="ex_forever">Never Expires</label>
                    <input  name="expiration" id="ex_forever" value=""  type="radio">
                    <label  for="ex_week">In one week</label>
                    <input name="expiration" id="ex_week" value="{{$next_week}}" type="radio">
                    <label for="ex_month">In one month</label>
                    <input name="expiration" id="ex_month" value="{{$next_month}}" type="radio">
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>


        <div class="row">
            <table class="url-table table table-striped table-light table-hover">
                <thead>
                <tr>
                    <th scope="col">Url</th>
                    <th scope="col">Created at</th>
                    <th scope="col">Clicks</th>
                    <th scope="col">Short Url</th>
                    <th scope="col">Stats</th>
                </tr>
                </thead>
                <tbody>
                @foreach($urls as $url)
                    <tr>
                        <td scope="row"><a href="{{$url->url}}">{{$url->url}}</a></td>
                        <td>{{$url->created_at}}</td>
                        <td>{{$url->count}}</td>
                        <td><a href="http://localhost:8000/{{$url->short_code}}">http://localhost:8000/{{$url->short_code}}</a></td>
                        <td>Stats</td>
                    </tr>
                @endforeach
                </tbody>
            </table>


        </div>
        {{now()->unix()}}

</div>
@endsection
