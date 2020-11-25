@extends('layouts.admin')

@section('title', 'Customers')

@section('content')
    <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Customers</h3>
            <div class="block-options">
                <button class="btn-block-option" data-toggle="modal" data-target="#exampleModal">
                    <i class="fa fa-user-plus"></i>
                </button>
                <button class="btn-block-option" onclick="printDiv()">
                    <i class="fa fa-print"></i>
                </button>
                <button class="btn-block-option buttonCsv">
                    <i class="fa fa-file-csv"></i>
                </button>
            </div>
        </div>
        <div class="block-content block-content-full" id="areaToPrint" style="overflow-x: scroll;">
            @if(Session::has('msg'))
                <div class="alert alert-info">
                    {{Session::get('msg')}}
                </div>
            @endif
            <div class="mb-4 d-flex justify-content-center">
                <form class="d-none d-sm-inline-block" method="GET">
                    <div class="input-group input-group-sm">
                        <input type="text" class="form-control " placeholder="Search.." id="page-header-search-input3" name="search_input" v-model="input">
                        <div class="input-group-append">
                            <span class="input-group-text bg-body border-0">
                                <i class="si si-magnifier"></i>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
            @if(count($customers)>0)
            <table border="1" cellpadding="5" class="table table-bordered table-striped table-vcenter" >
                <thead>
                    <tr>
                        <th class="text-center" style="width: 80px;">ID</th>
                        <th class="text-center" style="width: 80px;">Customer ID</th>
                        <th class="text-center" style="width: 80px;">Name</th>
                        <th class="text-center" style="width: 80px;">Email</th>
                        <th class="d-none d-sm-table-cell">Phone</th>
                        <th class="d-none d-sm-table-cell">Address</th>
                        <th class="d-none d-sm-table-cell">Region</th>
                        <th class="d-none d-sm-table-cell">Region ID</th>
                        <th class="d-none d-sm-table-cell">Actions</th>
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
                                <calculate-component :customer_id="{{$customer->id}}"></calculate-component>
                                <a href="{{route('admin.customer.edit', $customer->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="edit" data-original-title="Edit">
                                    <i class="fa fa-fw fa-pencil-alt"></i>
                                </a>
                                <a href="{{route('admin.customer.delete', $customer->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="delete" data-original-title="Delete">
                                    <i class="fa fa-fw fa-times"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
        @if($customers instanceof \Illuminate\Pagination\LengthAwarePaginator )
        <div class="ml-3 text-size-md float-right">
            {{ $customers->links() }}
        </div>
        @endif
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
                            <label for="exampleInputEmail2">Customer id</label>
                            <input type="text" name="customer_id" class="form-control" id="exampleInputEmail2" aria-describedby="emailHelp" placeholder="Enter customer_id">
                        </div>
                        <div class="form-group">
                            <label for="val-skill">Manager <span class="text-danger">*</span></label>
                            <select type="text" class="form-control"  id="val-skill" name="manager_id">
                                <option>Select manager</option>
                                @foreach($managers as $manager)
                                    <option value="{{$manager->id}}">{{ $manager->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail3">Email</label>
                            <input type="email" name="email" class="form-control" id="exampleInputEmail3" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword2">Phone</label>
                            <input type="text" name="phone" class="form-control" id="exampleInputPassword2" placeholder="Phone">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword3">Region</label>
                            <select type="text" class="form-control"  id="val-skill123" name="region">
                                <option>Select manager</option>
                                @foreach($regions as $region)
                                    <option value="{{$region->id}}">{{ $region->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        {{-- <div class="form-group">
                            <label for="exampleInputPassword4">Region id</label>
                            <input type="text" name="region_id" class="form-control" id="exampleInputPassword4" placeholder="Region id">
                        </div> --}}
                        <div class="custom-control custom-switch custom-control-primary custom-control-lg mb-2">
                        <input type="checkbox" class="custom-control-input" id="example-sw-custom-primary-lg2" name="sms_status"  checked>
                            <label class="custom-control-label" for="example-sw-custom-primary-lg2">Send sms</label>
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
    <script>
        function printDiv() {
            var divToPrint = document.getElementById('areaToPrint');
            newWin = window.open("");
            newWin.document.write(divToPrint.outerHTML);
            newWin.print();
            newWin.close();
        }
    </script>
    <script>
        function download_csv(csv, filename) {
            var csvFile;
            var downloadLink;

            // CSV FILE
            csvFile = new Blob([csv], {type: "text/csv"});

            // Download link
            downloadLink = document.createElement("a");

            // File name
            downloadLink.download = filename;

            // We have to create a link to the file
            downloadLink.href = window.URL.createObjectURL(csvFile);

            // Make sure that the link is not displayed
            downloadLink.style.display = "none";

            // Add the link to your DOM
            document.body.appendChild(downloadLink);

            // Lanzamos
            downloadLink.click();
        }

        function export_table_to_csv(html, filename) {
            var csv = [];
            var rows = document.querySelectorAll("table tr");
            
            for (var i = 0; i < rows.length; i++) {
                var row = [], cols = rows[i].querySelectorAll("td, th");
                
                for (var j = 0; j < cols.length; j++) 
                    row.push(cols[j].innerText);
                
                csv.push(row.join(","));		
            }

            // Download CSV
            download_csv(csv.join("\n"), filename);
        }

        document.querySelector(".buttonCsv").addEventListener("click", function () {
            var html = document.querySelector("table").outerHTML;
            export_table_to_csv(html, "table.csv");
        });
    </script>
@endsection