@extends('layouts.admin')

@section('title', 'Users')

@section('content')
<div class="block m-3">
    @if(session()->has('msg'))
        <div class="alert alert-primary mr-1 ml-1" role="alert">
            {{session()->get('msg')}}
        </div>
    @endif
    <div class="block-header">
        <h3 class="block-title">Managers</h3>
        <div class="block-options">
            <button class="btn-block-option" data-toggle="modal" data-target="#exampleModal">
                <i class="fa fa-user-plus fa-2x"></i>
            </button>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
            <thead>
                <tr>
                    <th class="text-center" style="width: 80px;">ID</th>
                    <th class="text-center" style="width: 80px;">Name</th>
                    <th class="text-center" style="width: 80px;">Email</th>
                    <th class="d-none d-sm-table-cell" style="width: 30%;">In charge</th>
                    <th class="text-center" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($managers as $manager)
                <tr>
                    <td class="text-center font-size-sm">{{ $manager->id }}</td>
                    <td class="font-w600 font-size-sm">
                        <a href="{{route('admin.manager.show', $manager->id)}}">{{ $manager->name }}</a>
                    </td>
                    <td class="font-w600 font-size-sm">
                        {{ $manager->email }}
                    </td>
                    <td class="d-none d-sm-table-cell font-size-sm">
                    <em class="text-muted">{{$manager->in_charge}}</em>
                    </td>
                    <td class="text-center">
                        <div class="btn-group">
                            <a href="{{route('admin.manager.show', $manager->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="edit" data-original-title="Edit">
                                <i class="fa fa-fw fa-pencil-alt"></i>
                            </a>
                            <a href="{{route('admin.manager.delete', $manager->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="delete" data-original-title="Delete">
                                <i class="fa fa-fw fa-times"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Manager</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.manager.save') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Manager name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">In charge</label>
                        <input type="text" name="in_charge" class="form-control" id="exampleInputPassword1" placeholder="Password">
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary float-right">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>
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
