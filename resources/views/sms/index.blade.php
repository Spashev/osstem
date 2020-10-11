@extends('layouts.admin')


@section('content')
    <table-component>
    </table-component>
    {{-- <div class="block m-3">
        <div class="block-header">
            <h3 class="block-title">Expiration Table</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <code>Sms notifications</code>
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
                        <th class="d-none d-sm-table-cell" style="width: 15%;">Confirmation</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($payments as $payment)
                        {{$payment->setReaminAndPaid()}}
                        <tr>
                            <th class="text-center" scope="row">{{$loop->iteration}}</th>
                            <td class="font-w600 font-size-sm">
                            <a href="be_pages_generic_profile.php">{{$payment->contract->contract_no}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                            <span>{{$payment->getTotalRemain()}}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>{{$payment->getTotalPaid()}}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span>{{ Str::substr($payment->deadline, 0, 11) }}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <div class="custom-control custom-switch custom-control-success custom-control-lg mb-2">
                                        <input type="checkbox" class="custom-control-input" id="example-sw-custom-success-lg2" name="sms_status" checked="">
                                        <label class="custom-control-label" for="example-sw-custom-success-lg2">SMS</label>
                                    </div>
                                </div>
                            </td>
                        </tr> 
                    @endforeach          
                </tbody>
            </table>
            {!! str_replace('/?', '?', $payments->links('vendor.pagination.bootstrap-4', ['last_page'=>$last_page])) !!}
        </div>
    </div> --}}
@endsection