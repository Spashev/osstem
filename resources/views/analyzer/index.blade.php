@extends('layouts.admin')

@section('content')
    <div class="block m-2">
        <div class="block-header">
            <h3 class="block-title">Analyzer</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <analuze-component></analuze-component>
            </div>
        </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{ asset('assets/js/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection

@section('script')
    <script src="{{ asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script>jQuery(function(){ One.helpers(['select2','datepicker']); });</script>
@endsection