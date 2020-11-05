@extends('layouts.admin')

@section('head')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/dropzone/dist/min/dropzone.min.css')}}">
@endsection

@section('content')
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Customers file</h3>
        </div>
        <div class="block-content block-content-full">
            <h2 class="content-heading border-bottom mb-4 pb-2">Asynchronous File Uploads</h2>
            <div class="row">
                <div class="col-lg-4">
                    <p class="font-size-sm text-muted">
                        Drag and drop sections for your file uploads
                    </p>
                </div>
                <div class="col-lg-8 col-xl-5">
                    <!-- DropzoneJS Container -->
                    <form class="dropzone dz-clickable" enctype="multipart/form-data"  action="{{route('admin.import')}}" method="POST">
                        @csrf
                        <div class="dz-default dz-message"><span>Drop files here to upload</span></div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script src="{{asset('assets/js/plugins/dropzone/dropzone.min.js')}}"></script>
@endsection