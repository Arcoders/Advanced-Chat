@extends('layouts.chat')

@section('application')

    <div class="green_background"></div>

    <global :auth_user="{{ Auth::user() }}"></global>

@endsection
