@extends('layouts.admin')

@section('content')
    <sms-history-component></sms-history-component>
@endsection
@section('script')
<script src="{{asset('js/download.js')}}"></script>
@endsection