@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Customers</h3>
            <div class="block-options">
                <button class="btn-block-option" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-user-plus fa-2x"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full">
            @if(Session::has('msg'))
                <div class="alert alert-info">
                    {{Session::get('msg')}}
                </div>
            @endif
            <!-- DataTables init on table by adding .js-dataTable-buttons class, functionality is initialized in js/pages/be_tables_datatables.min.js which was auto compiled from _es6/pages/be_tables_datatables.js -->
            <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th class="text-center" style="width: 80px;">Customer ID</th>
                        <th class="text-center" style="width: 80px;">Name</th>
                        <th>Email</th>
                        <th class="d-none d-sm-table-cell" style="width: 30%;">Phone</th>
                        <th class="d-none d-sm-table-cell" style="width: 30%;">Address</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Region</th>
                        <th style="width: 15%;">Region ID</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customers as $customer)
                    <tr>
                        <td class="text-center font-size-sm">{{ $customer->id }}</td>
                        <td class="text-center font-size-sm">{{ $customer->customer_id }}</td>
                        <td class="font-w600 font-size-sm">
                            <a href="{{route('admin.customer.show', $customer->id)}}">{{ $customer->name }}</a>
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm">
                        <em class="text-muted">{{$customer->email}}</em>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-success">{{ $customer->phone }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            {{ $customer->address }}
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-info font-size-lg">{{ $customer->region }}</span>
                        </td>
                        <td>
                            <em class="text-muted font-size-sm">{{ $customer->region_id }}</em>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="#" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="edit" data-original-title="Edit">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="#" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="delete" data-original-title="Delete">
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
                <h5 class="modal-title" id="exampleModalLabel">Customer</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{route('admin.customer.save') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="exampleInputEmail1">Customer name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Customer id</label>
                        <input type="text" name="customer_id" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter customer_id">
                    </div>
                    <div class="form-group">
                        <label for="val-skill">Manager <span class="text-danger">*</span></label>
                        <select type="text" name="manager_id" class="form-control"  id="val-skill" name="manager_id">
                            <option>Select manager</option>
                            @foreach($managers as $manager)
                                <option value="{{$manager->id}}">{{ $manager->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="exampleInputEmail1">Email</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword2">Phone</label>
                        <input type="text" name="phone" class="form-control" id="exampleInputPassword2" placeholder="Phone">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword3">Region</label>
                        <input type="text" name="region" class="form-control" id="exampleInputPassword3" placeholder="Region">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword4">Region id</label>
                        <input type="text" name="region_id" class="form-control" id="exampleInputPassword4" placeholder="Region id">
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
