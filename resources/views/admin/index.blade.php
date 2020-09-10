@extends('layouts.admin')

@section('content')
    <!-- Page Content -->
    <div class="content content-narrow">
        <!-- Stats -->
        <div class="messages"></div>
        <div class="row">
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Users</div>
                    <div class="font-size-h2 font-w400 text-dark">{{ $users->count() }}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Products</div>
                    <div class="font-size-h2 font-w400 text-dark">{{ $products->count() }}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Categories</div>
                    <div class="font-size-h2 font-w400 text-dark">{{ $categories->count() }}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Managers</div>
                    <div class="font-size-h2 font-w400 text-dark">{{ $managers->count() }}</div>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3 col-lg-6 col-xl-3">
                <a class="block block-rounded block-link-pop border-left border-primary border-4x" href="javascript:void(0)">
                    <div class="block-content block-content-full">
                        <div class="font-size-sm font-w600 text-uppercase text-muted">Customers</div>
                        <div class="font-size-h2 font-w400 text-dark">{{ $customers->count() }}</div>
                    </div>
                </a>
            </div>
        </div>
        <!-- END Stats -->

        <!-- Dashboard Charts -->
        <div class="row">
            <div class="col-lg-6">
                <div class="block block-rounded block-mode-loading-oneui">
                    <div class="block-header">
                        <h3 class="block-title">Earnings in $</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-0 bg-body-light text-center">
                        <!-- Chart.js is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js) -->
                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                        <div class="pt-3" style="height: 360px;"><canvas id="myChart"  class="js-chartjs-dashboard-earnings"></canvas></div>
                    </div>
                    <div class="block-content">
                        <div class="row items-push text-center py-3">
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-wallet fa-2x text-muted"></i>
                                <div class="text-muted mt-3">$148,000</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-angle-double-up fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+9% Earnings</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-ticket-alt fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+20% Tickets</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-users fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+46% Clients</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="block block-rounded block-mode-loading-oneui">
                    <div class="block-header">
                        <h3 class="block-title">Sales</h3>
                        <div class="block-options">
                            <button type="button" class="btn-block-option" data-toggle="block-option" data-action="state_toggle" data-action-mode="demo">
                                <i class="si si-refresh"></i>
                            </button>
                            <button type="button" class="btn-block-option">
                                <i class="si si-settings"></i>
                            </button>
                        </div>
                    </div>
                    <div class="block-content p-0 bg-body-light text-center">
                        <!-- Chart.js is initialized in js/pages/be_pages_dashboard.min.js which was auto compiled from _es6/pages/be_pages_dashboard.js) -->
                        <!-- For more info and examples you can check out http://www.chartjs.org/docs/ -->
                        <div class="pt-3" style="height: 360px;"><canvas class="js-chartjs-dashboard-sales"></canvas></div>
                    </div>
                    <div class="block-content">
                        <div class="row items-push text-center py-3">
                            <div class="col-6 col-xl-3">
                                <i class="fab fa-wordpress fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+20% Themes</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-font fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+25% Fonts</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-archive fa-2x text-muted"></i>
                                <div class="text-muted mt-3">-10% Icons</div>
                            </div>
                            <div class="col-6 col-xl-3">
                                <i class="fa fa-paint-brush fa-2x text-muted"></i>
                                <div class="text-muted mt-3">+8% Graphics</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- END Dashboard Charts -->

        <!-- END Customers and Latest Orders -->
    </div>
    <!-- END Page Content -->
    <button class="btn btn-danger" onclick="send();">websocket</button>
@endsection

@section('script')
<script src="{{ asset('assets/js/pages/be_pages_dashboard.min.js') }}"></script>
<script src="{{ asset('assets/js/plugins/chart.js/Chart.bundle.min.js') }}"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/sockjs-client/0.3.4/sockjs.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/stomp.js/2.3.3/stomp.min.js"></script>


<script src="{{asset('js/app.js')}}"></script>
<script>
        Echo.channel('home')
        .listen('NewMessage', (e) => {
            console.log(e.message)
        });
    
</script>
@endsection
