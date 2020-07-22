@extends('layouts.admin')

@section('title', 'Create product')

@section('content')
<div class="content">
    @if(session()->has('msg'))
        <div class="alert alert-success" role="alert">
            {{ session()->get('msg') }}
        </div>
    @endif
    <form action="{{route('admin.product.update', $product->id) }}" method="POST" class="js-validation pb-5" enctype="multipart/form-data" novalidate="novalidate">
        @csrf
        @method('PUT')
        <div class="block">
            <div class="block-content block-content-full">
                <div class="">
                    <!-- Regular -->
                    <div class="row items-push">
                        <div class="col-lg-4">
                            <p class="font-size-sm text-muted">
                                Enter all input for creating product.
                            </p>
                        </div>
                        <div class="col-lg-8 col-xl-5">
                            <div class="form-group">
                                <label for="val-username">Title <span class="text-danger">*</span></label>
                            <input  type="text" name="title"  class="form-control" id="val-username" name="val-username" value="{{$product->title}}">
                            </div>
                            <div class="form-group">
                                <label for="val-email">Price <span class="text-danger">*</span></label>
                                <input type="text" name="price" class="form-control" id="val-email" name="val-email" value="{{$product->price}}">
                            </div>
                            <div class="form-group">
                                <label for="val-password">Quantity <span class="text-danger">*</span></label>
                                <input type="number" name="quantity" class="form-control" id="val-password" name="val-password" value="{{$product->quantity}}">
                            </div>
                            <div class="form-group">
                                <label for="val-suggestions">Description <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" class="form-control" id="val-suggestions" name="val-suggestions" rows="5">{{$product->description}}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="val-skill">Category <span class="text-danger">*</span></label>
                                <select type="text" name="categories[]" class="form-control" multiple id="val-skill" name="val-skill">
                                    @foreach($categories as $category)
                                        <option value="{{$category->id}}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="val-skill">Currency <span class="text-danger">*</span></label>
                                <select type="text" name="currency" class="form-control" id="val-skill" name="val-skill">
                                    <option value="">Please select</option>
                                    <option value="KZT">KZT</option>
                                    <option value="USD">USD</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="val-username">Slug <span class="text-danger">*</span></label>
                                <input  type="text" name="code"  class="form-control" id="val-username" name="val-username" value="{{$product->code}}">
                            </div>
                            <div class="custom-file form-group mb-2">
                                <input type="file" name="images[]" class="custom-file-input js-custom-file-input-enabled" data-toggle="custom-file-input" id="example-file-input-multiple-custom" multiple="">
                                <label class="custom-file-label" for="example-file-input-multiple-custom">Choose files</label>
                            </div>
                            <div class="form-group custom-control custom-switch custom-control-primary custom-control-lg mb-2 ">
                                <input type="checkbox" name="is_published" class="custom-control-input" id="example-sw-custom-primary-lg2" name="example-sw-primary-lg2" checked="">
                                <label class="custom-control-label" for="example-sw-custom-primary-lg2">Is published</label>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!-- jQuery Validation -->
</div>
@endsection

@section('head')
<link rel="stylesheet" href="assets/js/plugins/select2/css/select2.min.css">
@endsection

@section('script')
    <script src="{{asset('assets/js/plugins/select2/js/select2.full.min.js') }}"></script>
    <script src="{{asset('assets/js/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{asset('assets/js/plugins/jquery-validation/additional-methods.js') }}"></script>

    <script>jQuery(function(){ One.helpers('select2'); });</script>

    <script src="{{asset('assets/js/pages/be_forms_validation.min.js') }}"></script>
@endsection