@extends('layouts.admin')

@section('content')
<div class="content content-boxed">
    <!-- Invoice -->
    <div class="block">
        <div class="block-header">
        <h3 class="block-title">ID-{{$customer->customer_id}}</h3>
            <div class="block-options">
                <!-- Print Page functionality is initialized in Helpers.print() -->
                <button type="button" class="btn-block-option" onclick="One.helpers('print');">
                    <i class="si si-printer mr-1"></i> Print Invoice
                </button>
            </div>
        </div>
        <div class="block-content">
            <div class="p-sm-4 p-xl-7">
                <!-- Invoice Info -->
                <div class="row mb-4">
                    <!-- Company Info -->
                    <div class="col-6 font-size-sm">
                    <p class="h3">Company</p>
                        <address>
                            Region<br>
                            Region id<br>
                            Phone<br>
                            Email
                        </address>
                    </div>
                    <!-- END Company Info -->

                    <!-- Client Info -->
                    <div class="col-6 text-right font-size-sm">
                        <p class="h3">{{$customer->name}}</p>
                        <address>
                            {{$customer->region}}<br>
                            {{$customer->region_id}}<br>
                            {{$customer->phone}}<br>
                            {{$customer->email}}
                        </address>
                    </div>
                    <!-- END Client Info -->
                </div>
                <!-- END Invoice Info -->

                <!-- Table -->
                <div class="table-responsive push">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 60px;"></th>
                                <th>Contract no</th>
                                <th class="text-center" style="width: 90px;">Seq</th>
                                <th class="text-right" style="width: 120px;">Amount</th>
                                <th class="text-right" style="width: 120px;">Payment date</th>
                                <th class="text-right" style="width: 120px;">Paid</th>
                                <th class="text-right" style="width: 120px;">Remain</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($customer->contracts as $contract)
                                @foreach($contract->payments as $payment)
                                <tr>
                                    <td class="text-center">{{$payment->id}}</td>
                                    <td>
                                    <p class="font-w600 mb-1">{{$contract->contract_no}}</p>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge badge-pill badge-primary">{{$payment->seq}}</span>
                                    </td>
                                    <td class="text-right">{{$payment->amount}} KZT</td>
                                    <td class="text-right">{{Str::substr($payment->payment_date,0,10)}}
                                        <div class="text-muted"><small>Deadline:<br> {{Str::substr($payment->deadline,0,10)}}</small></div>
                                    </td>
                                    <td class="text-right">{{$payment->paid}} KZT</td>
                                    <td class="text-right">{{$payment->remain}} KZT</td>
                                </tr>
                                @endforeach
                            @endforeach
                            <tr>
                                <td colspan="6" class="font-w700 text-uppercase text-right bg-body-light">Remain Total</td>
                                <td class="font-w700 text-right bg-body-light">{{$total}}</td>
                            </tr>
                            <tr>
                                <td colspan="6" class="font-w700 text-uppercase text-right bg-body-light">Total Due</td>
                                <td class="font-w700 text-right bg-body-light">{{$remain_total}}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END Table -->

                <!-- Footer -->
                <p class="font-size-sm text-muted text-center py-3 my-3 border-top">
                    Thank you very much for doing business with us. We look forward to working with you again!
                </p>
                <!-- END Footer -->
            </div>
        </div>
    </div>
    <!-- END Invoice -->
</div>
@endsection
