@extends('layouts.admin')

@section('head')
    <link rel="stylesheet" id="css-main" href="{{ asset('css/notification.css') }}">
@endsection

@section('content')
    <notify-component>
    </notify-component>
@endsection

@section('script')
<script src="{{asset('js/download.js')}}"></script>
@endsection