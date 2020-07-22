@extends('layouts.admin')

@section('title', 'Products')

@section('content')
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Dynamic Table <small>Export Buttons</small></h3>
            <div class="block-options">
                <a href="{{route('admin.product.create')}}" class="btn-block-option">
                    <i class="fa fa-cart-plus"></i>
                </button>
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
                        <th class="text-center" style="width: 80px;">Image</th>
                        <th>Title</th>
                        <th class="d-none d-sm-table-cell" style="width: 30%;">Price</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Quantity</th>
                        <th style="width: 15%;">Currency</th>
                        <th style="width: 15%;">Is_published</th>
                        <th style="width: 15%;">Created_at</th>
                        <th style="width: 15%;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                    <tr>
                        <td class="text-center font-size-sm">{{ $product->id }}</td>
                        <td class="text-center font-size-sm"><img src="{{ asset('storage/'.$product->images->first()->image) }}" alt="Product image" width="52" style="border-radius: 20%"></td>
                        <td class="font-w600 font-size-sm">
                            <a href="{{route('admin.product.show', $product->id)}}">{{ $product->title }}</a>
                        </td>
                        <td class="d-none d-sm-table-cell font-size-sm">
                        <em class="text-muted">{{$product->price}}</em>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-success">{{ $product->quantity }}</span>
                        </td>
                        <td class="d-none d-sm-table-cell">
                            <span class="badge badge-info">{{ $product->currency }}</span>
                        </td>
                        <td>
                            <em class="text-muted font-size-sm">{{ $product->is_published }}</em>
                        </td>
                        <td>
                            <em class="text-muted font-size-sm">{{ $product->created_at }}</em>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{route('admin.product.show', $product->id)}}"  class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="show" data-original-title="Show">
                                    <i class="far fa-eye"></i>
                                </a>
                                <a href="{{route('admin.product.edit', $product->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="edit" data-original-title="Edit">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="{{route('admin.product.delete', $product->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="delete" data-original-title="Delete">
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