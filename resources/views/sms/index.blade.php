@extends('layouts.admin')


@section('content')
    <div class="block m-3">
                <div class="block-header">
                    <h3 class="block-title">Bordered Table</h3>
                    <div class="block-options">
                        <div class="block-options-item">
                            <code>.table-bordered</code>
                        </div>
                    </div>
                </div>
                <div class="block-content">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 50px;">#</th>
                                <th>Contract_no</th>
                                <th class="text-center" style="width: 100px;">Remain</th>
                                <th class="text-center" style="width: 100px;">Paid</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Deadline</th>
                                <th class="d-none d-sm-table-cell" style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                            <tr>
                            <th class="text-center" scope="row">{{$loop->iteration}}</th>
                                <td class="font-w600 font-size-sm">
                                <a href="be_pages_generic_profile.php">{{$payment['contract_no']}}</a>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                <span>{{$payment['remain']}}</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span>{{$payment['paid']}}</span>
                                </td>
                                <td class="d-none d-sm-table-cell">
                                    <span>{{$payment['deadline']}}</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-light js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit Client">
                                            <i class="fa fa-fw fa-pencil-alt"></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-light js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Remove Client">
                                            <i class="fa fa-fw fa-times"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr> 
                            @endforeach          
                        </tbody>
                    </table>
                </div>
            </div>
@endsection