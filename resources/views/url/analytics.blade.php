@extends('layouts.app')
@section('content')
    {{$url}}
    <section id="auth-button"></section>
    <section id="view-selector"></section>
    <section id="timeline"></section>
@endsection

@section('bottom_script')
    <script src="{{asset('js/analytics.js')}}" type="text/javascript"></script>
@endsection