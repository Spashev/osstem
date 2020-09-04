@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Excel</h3>
            <div class="block-options">
                <div class="block-options-item">
                <a href="{{route('admin.excel.create')}}"><i class="fa fa-plus fa-2x"></i></a>
                </div>
            </div>
        </div>
        <div class="block-content">
            <div class="row">
                <div class="col-md-12 col-lg-12"  style="overflow: scroll; height:500px;">
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th style="width: 15%;">id</th>
                                <th style="width: 15%;">in_charge</th>
                                <th class= style="width: 15%;">manager</th>
                                <th style="width: 15%;">region</th>
                                <th style="width: 15%;">region_id</th>
                                <th class="text-center" style="width: 50px;">customer_id</th>
                                <th>cutomer_name</th>
                                <th class= style="width: 15%;">contract no</th>
                                <th class= style="width: 15%;">amount</th>
                                <th class= style="width: 15%;">seq</th>
                                <th class= style="width: 15%;">payment_date</th>
                                <th class= style="width: 15%;">deadline</th>
                                <th class= style="width: 15%;">paid</th>
                                <th class= style="width: 15%;">remain</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td class="font-size-md" scope="row">{!!$payment->id!!}</td>
                                    <td class="font-size-md" scope="row">{!!$payment->manager->in_charge ? $payment->manager->in_charge : '<span class="font-w700 badge badge-warning"">No manager</span>'!!}</td>
                                    <td class="font-size-md" scope="row">
                                        <a href="#">{!!$payment->manager->name ? $payment->manager->name : '<span class="font-w700 badge badge-danger">No manager</span>'!!}</a>
                                    </td>
                                    <td class="font-size-md">
                                        {!!$payment->customer->region ? $payment->customer->region : '<span class="font-w700 badge badge-warning"">No region</span>'!!}
                                    </td>
                                    <td class="ffont-size-md">
                                        {!!$payment->customer->region_id ? $payment->customer->region_id : '<span class="font-w700 badge badge-danger">No region</span>'!!}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->customer->customer_id}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->customer->name}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->contract_no}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->amount}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->seq}}
                                    </td>
                                    <td class="font-size-sm">
                                        {!!$payment->payment_date ? substr($payment->payment_date,0,-9) : '<span class="font-w700 badge badge-danger">No date</span>'!!}
                                    </td>
                                    <td class="font-size-sm">
                                        {{$payment->deadline ? substr($payment->deadline,0,-9) : '<span class="font-w700 badge badge-warning"">No date</span>'}}
                                    </td>
                                    <td class="font-size-lg">
                                        <span class="badge badge-success font-w700">{{$payment->paid}}</span>
                                    </td>
                                    <td class="font-size-lg">
                                        <span class="badge badge-danger font-w700">{{$payment->remain}}</span>
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <a href="{{route('admin.excel.edit',$payment->id)}}" class="btn btn-sm btn-light js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Edit Client">
                                                <i class="fa fa-fw fa-pencil-alt"></i>
                                            </a>
                                            <a href="{{route('admin.excel.delete', $payment->id)}}" class="btn btn-sm btn-light js-tooltip-enabled" data-toggle="tooltip" title="" data-original-title="Remove Client">
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
        </div>
        <div class="m-3">
            {{$payments->links()}}
        </div>
    </div>
</div>
@endsection
