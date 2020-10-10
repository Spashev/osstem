@extends('layouts.admin')

@section('content')
<div class="content">
    <form class="js-validation" action="{{ route('admin.manager.update', $manager->id) }}" method="POST" novalidate="novalidate">
        @csrf
        @method('PUT')
        <div class="block">
            <div class="block-content block-content-full">
                <!-- Regular -->
                <h2 class="content-heading border-bottom mb-4 pb-2">Manager Form</h2>
                <div class="row items-push">
                    <div class="col-lg-4">
                        <p class="font-size-sm text-muted">
                            Enter manager name, email and in_charge id.
                        </p>
                        @if (Session::has('msg'))
                            <div class="alert alert-success">{{Session::get('msg')}}</div>
                        @endif
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-9 col-xl-6">
                        <div class="form-group">
                            <label for="val-username">Name</label>
                        <input type="text" class="form-control" id="val-username" value="{{$manager->name}}" name="name" placeholder="Enter a manager name..">
                        </div>
                        <div class="form-group">
                            <label for="val-email">Email</label>
                            <input type="text" class="form-control" id="val-email" value="{{$manager->email}}" name="email" placeholder="Your email..">
                        </div>
                        <div class="form-group">
                            <label for="val-password">In_charge</label>
                            <input type="text" class="form-control" id="val-password" value="{{$manager->in_charge}}" name="in_charge" placeholder="Your in_charge..">
                        </div>
                    </div>
                </div>
                <!-- END Regular -->
                <!-- Submit -->
                <div class="row items-push">
                    <div class="col-lg-6 offset-lg-4">
                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </div>
                </div>
                <!-- END Submit -->
            </div>
        </div>
    </form>
    <!-- jQuery Validation -->
</div>
@endsection
