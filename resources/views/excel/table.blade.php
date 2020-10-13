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
                <form action="{{ route('admin.excel.filter') }}" method="post">
                    @csrf
                    <div class="form-group form-row">
                        <div class="col-3">
                            <input type="text" class="form-control form-control-alt" name="manager" placeholder="Manager">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control form-control-alt" name="customer" placeholder="Customer">
                        </div>
                        <div class="col-3">
                            <input type="text" class="form-control form-control-alt" name="contract_no" placeholder="Contract no">
                        </div>
                        <div class="col-2">
                            <input type="text" class="form-control form-control-alt" name="deadline" placeholder="Deadline">
                        </div>
                        <div class="col-1">
                            <button type="submit" class="btn btn-primary">FILTER</button>
                        </div>
                    </div>
                </form>
                <div class="col-md-12 col-lg-12" style="overflow-x: scroll;">
                    <div class="mb-4 d-flex justify-content-center">
                        <form class="d-none d-sm-inline-block" method="GET">
                            <div class="input-group input-group-sm">
                                <input type="text" class="form-control form-control-alt" placeholder="Search.." id="page-header-search-input3" name="search_input" v-model="input">
                                <div class="input-group-append">
                                    <span class="input-group-text bg-body border-0">
                                        <i class="si si-magnifier"></i>
                                    </span>
                                </div>
                            </div>
                        </form>
                    </div>
                    @if(count($payments) > 0)
                    <table class="table table-bordered table-vcenter">
                        <thead>
                            <tr>
                                <th style="width: 15%;">@sortablelink('id')</th>
                                <th style="width: 15%;">@sortablelink('in_charge')</th>
                                <th style="width: 15%;">@sortablelink('manager')</th>
                                <th style="width: 15%;">@sortablelink('region')</th>
                                <th>@sortablelink('cutomer_name')</th>
                                <th style="width: 15%;">@sortablelink('contract_no')</th>
                                <th style="width: 15%;">@sortablelink('amount')</th>
                                <th style="width: 15%;">@sortablelink('seq')</th>
                                <th style="width: 15%;">@sortablelink('deadline')</th>
                                <th style="width: 15%;">@sortablelink('paid')</th>
                                <th style="width: 15%;">@sortablelink('remain')</th>
                                <th class="text-center" style="width: 100px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($payments as $payment)
                                <tr>
                                    <td class="font-size-md" scope="row">{!!$payment->id!!}</td>
                                    <td class="font-size-md" scope="row">{!!$payment->contract->manager->in_charge ? $payment->contract->manager->in_charge : '<span class="font-w700 badge badge-warning">No manager</span>'!!}</td>
                                    <td class="font-size-md" scope="row">
                                        <a href="{{route('admin.manager.show', $payment->contract->manager->id)}}">{!!$payment->contract->manager->name ? $payment->contract->manager->name : '<span class="font-w700 badge badge-danger">No manager</span>'!!}</a>
                                    </td>
                                    <td class="font-size-md">
                                        {!!$payment->contract->customer->region ? $payment->contract->customer->region : '<span class="font-w700 badge badge-warning">No region</span>'!!}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->contract->customer->name}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->contract->contract_no}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->amount}}
                                    </td>
                                    <td class="font-size-md">
                                        {{$payment->seq}}
                                    </td>
                                    <td class="font-size-sm">
                                        {!!$payment->deadline ? substr($payment->deadline,0,-9) : '<span class="font-w700 badge badge-warning">No date</span>'!!}
                                    </td>
                                    <td class="font-size-md">
                                        <span class="badge badge-success font-w700">{{$payment->paid}}</span>
                                    </td>
                                    <td class="font-size-md">
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
                    @endif
                </div>
                @if($payments instanceof \Illuminate\Pagination\LengthAwarePaginator )
                    <div class="ml-3 mt-3 text-size-md">
                        {{ $payments->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
