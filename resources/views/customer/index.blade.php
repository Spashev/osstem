@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Customers</h3>
            <div class="block-options">
                <button type="button" class="btn-block-option">
                    <i class="si si-settings"></i>
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
                            <span class="badge badge-info">{{ $customer->region }}</span>
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
