@extends('layouts.admin')

@section('content')
    <div class="content">
        <div class="block">
            <div class="block-content">
                <div class="row items-push">
                    <div class="col-md-6">
                        <!-- Images -->
                        <!-- Magnific Popup (.js-gallery class is initialized in Helpers.magnific()) -->
                        <!-- For more info and examples you can check out http://dimsemenov.com/plugins/magnific-popup/ -->
                        <div class="row gutters-tiny js-gallery img-fluid-100 js-gallery-enabled">
                            <div class="col-12 mb-3">
                                <a class="img-link img-link-zoom-in img-lightbox" href="#" onclick="return false;">
                                    <img class="img-fluid" src="{{asset('assets/media/various/ecom_product6.png')}}" alt="">
                                </a>
                            </div>
                        </div>
                        <!-- END Images -->
                    </div>
                    <div class="col-md-6">
                        <!-- Info -->
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                            <div class="font-size-sm font-w600 text-success">{{ strtoupper(implode(", ", $user->getRoleNames()->toArray())) }}</div>
                            <div class="font-size-h3 text-muted">Created: {{ $user->created_at->format('Y-m-d') }}</div>
                            </div>
                        </div>
                        <div class="font-size-h4 font-w700">
                            Email: {{$user->email}}
                        </div>
                        <form class="d-flex justify-content-between my-3 border-top border-bottom" action="be_pages_ecom_store_product.php" method="post" onsubmit="return false;">
                            <div class="py-3">
                                <select class="form-control form-control-sm" id="ecom-license" name="ecom-license" size="1">
                                    <option value="0" disabled="" selected="">LICENSE</option>
                                    <option value="simple">Simple</option>
                                    <option value="multiple">Multiple</option>
                                </select>
                            </div>
                            <div class="py-3">
                                <button type="submit" class="btn btn-sm btn-light">
                                    <i class="fa fa-plus text-success mr-1"></i> Add to Cart
                                </button>
                            </div>
                        </form>
                        <p>Dolor posuere proin blandit accumsan senectus netus nullam curae, ornare laoreet adipiscing luctus mauris adipiscing pretium eget fermentum, tristique lobortis est ut metus lobortis tortor tincidunt himenaeos habitant quis dictumst proin odio sagittis purus mi, nec taciti vestibulum quis in sit varius lorem sit metus mi.</p>
                        <!-- END Info -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection