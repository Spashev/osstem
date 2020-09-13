@extends('layouts.admin')

@section('content')
<div class="content">
    <main id="main-container">
        <div class="bg-body-light">
            <div class="content content-full">
                <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
                    <h1 class="flex-sm-fill h3 my-2">
                        Customer <small class="d-block d-sm-inline-block mt-2 mt-sm-0 font-size-base font-w400 text-muted">HISTORY.</small>
                    </h1>
                    <div class="flex-sm-00-auto ml-sm-3 btn-group" aria-label="breadcrumb">
                        <button class="btn btn-sm btn-primary" data-toggle="class-toggle" data-target=".timeline" data-class="timeline-centered">
                            <i class="fa fa-arrows-alt-h mr-1 fa-2x"></i>
                        </button>
                        <a class="btn btn-sm btn-primary buttonCsv" href="{{route('admin.customer.csv', $customer->id)}}">
                            <i class="fa fa-file-csv mr-1 fa-2x"></i>
                        </a>
                        <a class="btn btn-sm btn-primary" href="{{route('admin.customer.invoice', $customer->id)}}">
                            <i class="fa fa-file-invoice mr-1 fa-2x"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <ul class="timeline timeline-alt">
                @foreach($customer->contracts->sortDesc() as $contract)
                    @foreach($contract->payments as $payment)
                        <li class="timeline-event">
                            <div class="timeline-event-icon bg-info">
                                <i class="fab fa-twitter"></i>
                            </div>
                            <div class="timeline-event-block block invisible" data-toggle="appear">
                                <div class="block-header block-header-default">
                                <h3 class="block-title">{{$contract->contract_no}}</h3>
                                    <div class="block-options">
                                        <div class="timeline-event-time block-options-item font-size-sm font-w600">
                                            {{Str::substr($payment->payment_date, 0, 10)}}
                                        </div>
                                    </div>
                                </div>
                                <div class="block-content">
                                    <div class="row">
                                        <div class="col-md-6">
                                        <a class="font-w600" href="javascript:void(0)">{{$customer->name}}</a>
                                            <ul class="nav-items push">
                                                <li>
                                                    <a class="media py-2" href="javascript:void(0)">
                                                        <div class="item item-rounded bg-success text-white mx-auto">
                                                            <i class="fab fa-2x fa-bitcoin"></i>
                                                        </div>
                                                        <div class="media-body ml-2">
                                                            <div class="font-size-sm text-muted">Amount</div>
                                                            <div class="font-w600">{{$payment->amount}} KZT</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="media py-2" href="javascript:void(0)">
                                                        <div class="item item-rounded bg-body text-success mx-auto">
                                                            <i class="fa fa-calculator fa-2x text-muted"></i>
                                                        </div>
                                                        <div class="media-body ml-2">
                                                            <div class="font-w400 font-size-sm text-muted">Remain</div>
                                                        <div class="font-w600">{{$payment->remain}} KZT</div>

                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="media py-2" href="javascript:void(0)">
                                                        <div class="item item-rounded bg-warning text-white mx-auto">
                                                            <i class="fa fa-percent fa-2x"></i>
                                                        </div>
                                                        <div class="media-body ml-2">
                                                            <div class="font-w400 font-size-sm text-muted">Percent</div>
                                                        <div class="font-w600">{{$payment->percent}} %</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-6">
                                            <br>
                                            <ul class="nav-items push">
                                                <li>
                                                    <a class="media py-2" href="javascript:void(0)">
                                                        <div class="mr-3 ml-2 overlay-container overlay-left">
                                                            <div class="item item-rounded bg-info-light text-info mx-auto">
                                                                <i class="si fa-2x si-calendar"></i>
                                                            </div>
                                                        </div>
                                                        <div class="media-body">
                                                            <div class="font-w400 font-size-sm text-muted">Seq</div>
                                                        <div class="font-w600">{{$payment->seq}}</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="media py-2 ml-2" href="javascript:void(0)">
                                                        <div class="item item-rounded bg-dark text-white mx-auto">
                                                            <i class="fa fa-money-bill-alt fa-2x"></i>
                                                        </div>
                                                        <div class="media-body ml-2">
                                                            <div class="font-size-sm text-muted">Paid</div>
                                                        <div class="font-w600">{{$payment->paid}} KZT</div>
                                                        </div>
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="media py-2 ml-2" href="javascript:void(0)">
                                                        <div class="item item-rounded bg-danger-light text-danger mx-auto">
                                                            <i class="fa fa-money-check-alt fa-2x"></i>
                                                        </div>
                                                        <div class="media-body ml-2">
                                                            <div class="font-w400 font-size-sm text-muted">Amount with percent</div>
                                                        <div class="font-w600">{{$payment->amount_percent}} KZT</div>
                                                        </div>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>
    </main>
</div>
@endsection

@section('head')
    <!-- Page JS Plugins CSS -->
    <link rel="stylesheet" href="{{asset('assets/js/plugins/magnific-popup/magnific-popup.css')}}">
@endsection

@section('script')
    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/magnific-popup/jquery.magnific-popup.min.js')}}"></script>
    <script>jQuery(function(){ One.helpers('magnific-popup'); });</script>
@endsection
