@extends('layouts.admin')

@section('title', 'Category')

@section('content')
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Dynamic Table <small>Export Buttons</small></h3>
        </div>
        <div class="block-content block-content-full">
            <table class="table table-bordered table-striped table-vcenter js-dataTable-buttons">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th style="width: 80px;">Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 30%;">Code</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td class="font-size-sm">{{ $category->id }}</td>
                            <td class="font-w600 font-size-sm">
                                <a href="be_pages_generic_blank.html">{{ $category->name }}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge badge-success">{{ $category->code }}</span>
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