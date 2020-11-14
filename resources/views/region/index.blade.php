@extends('layouts.admin')

@section('title', 'Region')

@section('content')
<div class="block m-5">
    <div class="block-header">
        <h3 class="block-title">Region <small>list</small></h3>
    </div>
    <div class="block-content block-content-full">
        <button type="button" class="btn btn-sm btn-rounded btn-primary  float-right mr-3" data-toggle="modal" data-target="#modal-block-normal"><i class="fa fa-plus"></i></button>
        <table class="table table-bordered table-striped table-vcenter js-dataTable-full-pagination">
            <thead>
                <tr>
                    <th class="text-center font-size-md" style="width: 80px;">ID</th>
                    <th class="font-size-md">Region</th>
                    <th class="font-size-md" style="width: 30%;">Region id</th>
                    <th class="font-size-md" style="width: 15%;">Created</th>
                    <th class="font-size-md" style="width: 15%;">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($regions as $region)
                <tr>
                    <td class="text-center font-size-md">{{$region->id}}</td>
                    <td class="font-w600 font-size-md">{{$region->name ? $region->name : 'no name'}}</td>
                    <td class="font-size-md">
                        {{$region->region_id ? $region->region_id : 'no region'}}
                    </td>
                    <td class="font-size-md">
                        {{$region->created_at->diffForHumans()}}
                    </td>
                    <td class="font-size-sm">
                        <div class="btn-group" role="group" aria-label="Icons Outline File group">
                            <a href="{{route('admin.region.edit', $region->id)}}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-fw fa-edit"></i>
                            </a>
                            <a href="{{route('admin.region.delete', $region->id)}}" class="btn btn-sm btn-outline-secondary">
                                <i class="fa fa-fw fa-trash-alt"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Normal Block Modal -->
<div class="modal m-3" id="modal-block-normal" tabindex="-1" role="dialog" aria-labelledby="modal-block-normal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="block block-themed block-transparent mb-0">
                <div class="block-header bg-primary-dark font-size-md">
                    <h3 class="block-title">Create region</h3>
                    <div class="block-options">
                        <button type="button" class="btn-block-option" data-dismiss="modal" aria-label="Close">
                            <i class="fa fa-fw fa-times"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content font-size-md">
                    <form class="mb-3" action="{{route('admin.region.store')}}" method="POST">
                        @csrf
                        <div class="form-group">
                          <label for="exampleInputEmail1">Region name</label>
                          <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Title">
                          <small id="emailHelp" class="form-text text-muted">Enter correct region name.</small>
                        </div>
                        <div class="form-group">
                          <label for="exampleInputPassword1">Region id</label>
                          <input type="text" name="region_id" class="form-control" id="exampleInputPassword1" placeholder="Id">
                        </div>
                        <button type="submit" class="btn btn-primary"><i class="fa fa-check mr-1"></i>add</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END Normal Block Modal -->
@endsection

@section('header')
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/dataTables.bootstrap4.css')}}">
    <link rel="stylesheet" href="{{asset('js/plugins/datatables/buttons-bs4/buttons.bootstrap4.min.css')}}">
@endsection
@section('script')

    <!-- Page JS Plugins -->
    <script src="{{ asset('assets/js/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/buttons/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.print.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/datatables/buttons/buttons.colVis.min.js') }}"></script>

    <!-- Page JS Code -->
    <script src="{{ asset('assets/js/pages/be_tables_datatables.min.js') }}"></script>
@endsection
