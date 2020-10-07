@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <!-- Progress Wizard -->
            <div class="js-wizard-simple block block">
                <!-- Step Tabs -->
                <ul class="nav nav-tabs nav-tabs-block nav-justified" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" href="#wizard-progress-step1" data-toggle="tab">1. Customer</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#wizard-progress-step3" data-toggle="tab">2. Payment</a>
                    </li>
                </ul>
                <!-- END Step Tabs -->

                <!-- Form -->
                <form action="{{route('admin.excel.save')}}" method="POST">
                    @csrf
                    <!-- Wizard Progress Bar -->
                    <div class="block-content block-content-sm">
                        <div class="progress" data-wizard="progress" style="height: 8px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 34.3333%;" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <!-- END Wizard Progress Bar -->

                    <!-- Steps Content -->
                    <div class="block-content block-content-full tab-content px-md-5" style="min-height: 300px;">
                        <!-- Step 1 -->
                        <div class="tab-pane active" id="wizard-progress-step1" role="tabpanel">
                            <div class="form-group">
                                <label for="example-select1 wizard-progress-firstname">Customer name</label>
                                <select class="form-control" id="example-select1" name="customer">
                                    <option value="0">Please select</option>
                                    @foreach ($customers as $customer)
                                        <option value="{{$customer->id}}">{{$customer->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="example-select2 wizard-progress-firstname">Manager</label>
                                <select class="form-control" id="example-select2" name="manager">
                                    <option value="0">Please select</option>
                                    @foreach ($managers as $manager)
                                        <option value="{{$manager->id}}">{{$manager->name}}</option>
                                    @endforeach
                                </select>
                            </div>
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
                        <!-- END Step 1 -->
                        <!-- Step 3 -->
                        <div class="tab-pane" id="wizard-progress-step3" role="tabpanel">
                            <div class="form-group">
                                <label for="wizard-progress-location1">Contract no</label>
                                <input class="form-control" type="text" id="wizard-progress-location1" name="contract_no">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location2">Seq</label>
                                <input class="form-control" type="number" id="wizard-progress-location2" name="seq" value="1">
                            </div>
                            <div class="form-group">
                                <label for="wizard-progress-location2">Amount</label>
                                <input class="form-control" type="number" id="wizard-progress-location2" name="amount" placeholder="KZT">
                            </div>
                            <div class="form-group">
                                <div class="form-group">
                                    <div class="input-daterange input-group" data-date-format="mm/dd/yyyy" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                        <input type="text" class="form-control" id="example-daterange1" name="payment_day" placeholder="Payment day" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                        <div class="input-group-prepend input-group-append">
                                            <span class="input-group-text font-w600">
                                                <i class="fa fa-fw fa-arrow-right"></i>
                                            </span>
                                        </div>
                                        <input type="text" class="form-control" id="example-daterange2" name="deadline" placeholder="Deadline" data-week-start="1" data-autoclose="true" data-today-highlight="true">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- END Step 3 -->
                    </div>
                    <!-- END Steps Content -->

                    <!-- Steps Navigation -->
                    <div class="block-content block-content-sm block-content-full bg-body-light rounded-bottom">
                        <div class="row">
                            <div class="col-6">
                                <button type="button" class="btn btn-secondary disabled" data-wizard="prev">
                                    <i class="fa fa-angle-left mr-1"></i> Previous
                                </button>
                            </div>
                            <div class="col-6 text-right">
                                <button type="button" class="btn btn-secondary" data-wizard="next">
                                    Next <i class="fa fa-angle-right ml-1"></i>
                                </button>
                                <button type="submit" class="btn btn-primary d-none" data-wizard="finish">
                                    <i class="fa fa-check mr-1"></i> Save
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- END Steps Navigation -->
                </form>
                <!-- END Form -->
            </div>
            <!-- END Progress Wizard -->
        </div>
    </div>
</div>
@endsection

@section('head')
    <link rel="stylesheet" href="{{asset('assets/js/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection

@section('script')
    <!-- Page JS Plugins -->
    <script src="{{asset('assets/js/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-bootstrap-wizard/bs4/jquery.bootstrap.wizard.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-validation/jquery.validate.min.js')}}"></script>
    <script src="{{asset('assets/js/plugins/jquery-validation/additional-methods.js')}}"></script>


    <!-- Page JS Code -->
    <script src="{{asset('assets/js/pages/be_forms_wizard.min.js')}}"></script>
    <script>
        jQuery(function(){
            One.helpers([
                'datepicker'
            ]);
        });
    </script>
@endsection
