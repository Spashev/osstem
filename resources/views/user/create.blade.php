@extends('layouts.admin')

@section('title', 'Create user')

@section('content')
<div class="block mt-3 mr-3 ml-3">
    <div class="block-content">
        @if(session()->has('msg'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('msg') }}
            </div>
        @endif
        <div class="row items-push">
            <div class="col-lg-2">
            </div>
            <div class="col-lg-10 col-xl-8 col-md-10">
                <form action="{{route('admin.user.create') }}" method="POST" class="pb-5">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">User name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection