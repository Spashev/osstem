@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Customer</h3>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-lg-3">
                    @if(Session::has('msg'))
                        <div class="alert alert-info">
                            {{Session::get('msg')}}
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <form action="{{route('admin.customer.update', $customer->id) }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Customer name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="{{$customer->name}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail2">Customer id</label>
                            <input type="text" name="customer_id" class="form-control" id="exampleInputEmail2" aria-describedby="emailHelp" value="{{$customer->customer_id}}">
                        </div>
                        <div class="form-group">
                            <label for="val-skill">Manager <span class="text-danger">*</span></label>
                            <select type="text" name="manager_id" class="form-control"  id="val-skill" name="manager_id">
                                <option>Select mana ger</option>
                                @foreach($managers as $manager)
                                    <option {{ $manager->id == $customer->manager_id ? 'selected' : '' }} value="{{$manager->id}}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail3">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail3" aria-describedby="emailHelp" value="{{$customer->email}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">Phone</label>
                            <input type="text" name="phone" class="form-control" id="exampleInputPassword2" value="{{$customer->phone}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword3">Region</label>
                            <input type="text" name="region" class="form-control" id="exampleInputPassword3" value="{{$customer->region}}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword4">Region id</label>
                            <input type="text" name="region_id" class="form-control" id="exampleInputPassword4" value="{{$customer->region_id}}">
                        </div>
                        <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                            <input type="checkbox" class="custom-control-input" id="example-sw-custom-primary-lg2" name="sms_status" {{$customer->sms_status == 'on' ? 'checked' : ''}}>
                            <label class="custom-control-label" for="example-sw-custom-primary-lg2">Send sms</label>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary float-right">Save</button>
                    </form>
                </div>
                <div class="col-lg-3">
                </div>
            </div>
        </div>
    </div>
</div>
@endsection