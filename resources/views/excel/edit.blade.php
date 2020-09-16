@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="block">
        <div class="block-header">
            <h3 class="block-title">Edit</h3>
        </div>
        <div class="block-content block-content-full">
            <div class="row">
                <div class="col-lg-4">
                    @if (Session::has('msg'))
                        <div class="alert alert-success">{{Session::get('msg')}}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
                <div class="col-lg-7">
                    <!-- Form Labels on top - Default Style -->
                <form class="mb-5" action="{{route('admin.excel.update', $payment->id)}}" method="GET">
                        <div class="form-group">
                            <label for="example-select1 wizard-progress-firstname">Customer name</label>
                            <input class="form-control" readonly type="text" id="wizard-progress-location1" name="contract_no" value="{{$payment->contract->customer->name}}">
                        </div>
                        <div class="form-group">
                            <label for="example-select2 wizard-progress-firstname">Manager</label>
                            <select class="form-control" id="example-select2" name="manager">
                                @foreach ($managers as $manager)
                                    <option {{$manager->id == $payment->manager_id ? 'selected="selected" ' : ''}}  value="{{$manager->id}}">{{$manager->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location1">Contract no</label>
                            <input class="form-control" type="text" id="wizard-progress-location1" name="contract_no" value="{{$payment->contract_no}}">
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location2">Seq</label>
                            <input class="form-control" readonly type="number" id="wizard-progress-location2" name="seq" value="{{$payment->seq}}">
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location2">Amount</label>
                            <input class="form-control" type="number" id="wizard-progress-location2" name="amount" value="{{$payment->amount}}">
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location2">Paid</label>
                            <input class="form-control" type="number" id="wizard-progress-location2" name="paid" value="{{$payment->paid}}">
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location2">Percent</label>
                            <input class="form-control" readonly type="text" id="wizard-progress-location2" name="percent" value="{{$payment->percent}}">
                        </div>
                        <div class="form-group">
                            <label for="wizard-progress-location2">Delay</label>
                            <input class="form-control" readonly type="text" id="wizard-progress-location2" value="{{$amount_percent}}">
                        </div>
                        <div class="form-group">
                            <div class="form-group">
                                <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                <input type="text" class="form-control" id="example-daterange1" name="payment_day" placeholder="Payment day" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{$payment->payment_date}}">
                                    <div class="input-group-prepend input-group-append">
                                        <span class="input-group-text font-w600">
                                            <i class="fa fa-fw fa-arrow-right"></i>
                                        </span>
                                    </div>
                                    <input type="text" class="form-control" id="example-daterange2" name="deadline" placeholder="Deadline" data-week-start="1" data-autoclose="true" data-today-highlight="true" value="{{$payment->deadline}}">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary float-right">Edit</button>
                        </div>
                    </form>
                    <!-- END Form Labels on top - Default Style -->
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection

@section('head')
    <link rel="stylesheet" href="assets/js/plugins/flatpickr/flatpickr.min.css">
    <link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection

@section('script')
    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-validation/additional-methods.js')}}"></script>
    <script src="assets/js/plugins/flatpickr/flatpickr.min.js"></script>


    <!-- Page JS Code -->
    <script src="{{asset('assets/js/pages/be_forms_wizard.min.js')}}"></script>
    <script>
        jQuery(function(){
            One.helpers([
                'datepicker','flatpickr'
            ]);
        });
    </script>
@endsection
