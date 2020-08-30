@extends('layouts.admin')

@section('title', 'Create category')

@section('content')
<div class="block m-3">
    <div class="block-header">
        <h3 class="block-title">Create category</h3>
    </div>
    <div class="block-content block-content-full">
        @if(session()->has('msg'))
            <div class="alert alert-success" role="alert">
                {{ session()->get('msg') }}
            </div>
        @endif
        <form action="{{ route('admin.category.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <p class="font-size-sm text-muted">
                        Enter category name.
                    </p>
                </div>
                <div class="col-lg-8 col-xl-6">
                    <div class="form-group">
                        <label for="example-text-input-alt">Name</label>
                        <input type="text" name="name" class="form-control form-control-alt" id="example-text-input-alt" name="example-text-input-alt" placeholder="Text name">
                    </div>
                    <div class="form-group">
                        <label for="example-password-input-alt">Code</label>
                        <input type="text" name="code" class="form-control form-control-alt" id="example-password-input-alt" name="example-password-input-alt" placeholder="Input code">
                    </div>
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection