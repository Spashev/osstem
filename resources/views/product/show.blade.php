@extends('layouts.admin')

@section('content')
<div class="block m-5">
    <div class="block-content">
        <!-- Vitals -->
        <div class="row items-push">
            <div class="col-md-6">
                <!-- Images -->
                <!-- Magnific Popup (.js-gallery class is initialized in Helpers.magnific()) -->
                <!-- For more info and examples you can check out http://dimsemenov.com/plugins/magnific-popup/ -->
                <div class="row gutters-tiny js-gallery img-fluid-100 js-gallery-enabled">
                    <div class="col-12 mb-3">
                        <a class="img-link img-link-zoom-in img-lightbox" href="assets/media/various/ecom_product6.png">
                            <img class="img-fluid" src="assets/media/various/ecom_product6.png" alt="">
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="img-link img-link-zoom-in img-lightbox" href="assets/media/various/ecom_product6_a.png">
                            <img class="img-fluid" src="assets/media/various/ecom_product6_a.png" alt="">
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="img-link img-link-zoom-in img-lightbox" href="assets/media/various/ecom_product6_b.png">
                            <img class="img-fluid" src="assets/media/various/ecom_product6_b.png" alt="">
                        </a>
                    </div>
                    <div class="col-4">
                        <a class="img-link img-link-zoom-in img-lightbox" href="assets/media/various/ecom_product6_c.png">
                            <img class="img-fluid" src="assets/media/various/ecom_product6_c.png" alt="">
                        </a>
                    </div>
                </div>
                <!-- END Images -->
            </div>
            <div class="col-md-6">
                <!-- Info -->
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <div class="font-size-sm font-w600 text-success">IN STOCK</div>
                        <div class="font-size-sm text-muted">200 Available</div>
                    </div>
                    <div class="font-size-h2 font-w700">
                        $58
                    </div>
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
        <!-- END Vitals -->

        <!-- Author -->
        <div class="block block-rounded block-bordered">
            <div class="block-content block-content-full d-flex justify-content-between align-items-center">
                <div>
                    <div class="mb-2">
                        By <a class="font-w600" href="javascript:void(0)">Emma Cooper</a>
                    </div>
                    <div>
                        <a class="btn btn-sm btn-light" href="javascript:void(0)">
                            <i class="fa fa-fw fa-plus text-success"></i>
                        </a>
                        <a class="btn btn-sm btn-light" href="javascript:void(0)">
                            <i class="fa fa-fw fa-envelope text-muted"></i>
                        </a>
                    </div>
                </div>
                <img class="img-avatar" src="assets/media/avatars/avatar2.jpg" alt="">
            </div>
        </div>
        <!-- END Author -->

        <!-- Extra Info Tabs -->
        <!-- Bootstrap Tabs (data-toggle="tabs" is initialized in Helpers.coreBootstrapTabs()) -->
        <div class="block">
            <ul class="nav nav-tabs nav-tabs-alt align-items-center js-tabs-enabled" data-toggle="tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" href="#ecom-product-info">Info</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#ecom-product-comments">Comments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#ecom-product-reviews">Reviews</a>
                </li>
            </ul>
            <div class="block-content tab-content">
                <!-- Info -->
                <div class="tab-pane pull-x active" id="ecom-product-info" role="tabpanel">
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th colspan="2">File Formats</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 20%;">Sketch</td>
                                <td>
                                    <i class="fa fa-check text-success"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>PSD</td>
                                <td>
                                    <i class="fa fa-check text-success"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>PDF</td>
                                <td>
                                    <i class="fa fa-times text-danger"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>AI</td>
                                <td>
                                    <i class="fa fa-check text-success"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>EPS</td>
                                <td>
                                    <i class="fa fa-check text-success"></i>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <table class="table table-striped table-borderless">
                        <thead>
                            <tr>
                                <th colspan="2">Extra</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="width: 20%;">
                                    <i class="fa fa-fw fa-calendar text-muted mr-1"></i> Date
                                </td>
                                <td>January 15, 2019</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-fw fa-anchor text-muted mr-1"></i> File Size
                                </td>
                                <td>265.36 MB</td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-fw fa-vector-square text-muted mr-1"></i> Vector
                                </td>
                                <td>
                                    <i class="fa fa-fw fa-check text-success"></i>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <i class="fa fa-fw fa-expand text-muted mr-1"></i> Dimensions
                                </td>
                                <td>
                                    <div class="mb-2">16 x 16 px</div>
                                    <div class="mb-2">32 x 32 px</div>
                                    <div class="mb-2">64 x 64 px</div>
                                    <div class="mb-2">128 x 128 px</div>
                                    <div>256 x 256 px</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- END Info -->

                <!-- Comments -->
                <div class="tab-pane pull-x font-size-sm" id="ecom-product-comments" role="tabpanel">
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar3.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <a class="font-w600" href="javascript:void(0)">Carol White</a>
                            <mark class="font-w600 text-danger">Purchased</mark>
                            <p class="my-1">High quality, thanks so much for sharing!</p>
                            <a class="mr-1" href="javascript:void(0)">Like</a>
                            <a class="mr-1" href="javascript:void(0)">Reply</a>
                            <span class="text-muted"><em>12 min ago</em></span>
                            <div class="media mt-3">
                                <a class="mr-3" href="javascript:void(0)">
                                    <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar2.jpg" alt="">
                                </a>
                                <div class="media-body">
                                    <a class="font-w600" href="javascript:void(0)">Emma Cooper</a>
                                    <mark class="font-w600 text-success">Author</mark>
                                    <p class="my-1">Thanks so much!!</p>
                                    <a class="mr-1" href="javascript:void(0)">Like</a>
                                    <a class="mr-1" href="javascript:void(0)">Reply</a>
                                    <span class="text-muted"><em>20 min ago</em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar12.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <a class="font-w600" href="javascript:void(0)">Jack Estrada</a>
                            <mark class="font-w600 text-danger">Purchased</mark>
                            <p class="my-1">Great work, thank you!</p>
                            <a class="mr-1" href="javascript:void(0)">Like</a>
                            <a class="mr-1" href="javascript:void(0)">Reply</a>
                            <span class="text-muted"><em>30 min ago</em></span>
                            <div class="media mt-3">
                                <a class="mr-3" href="javascript:void(0)">
                                    <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar2.jpg" alt="">
                                </a>
                                <div class="media-body">
                                    <a class="font-w600" href="javascript:void(0)">Emma Cooper</a>
                                    <mark class="font-w600 text-success">Author</mark>
                                    <p class="my-1">Thanks so much!!</p>
                                    <a class="mr-1" href="javascript:void(0)">Like</a>
                                    <a class="mr-1" href="javascript:void(0)">Reply</a>
                                    <span class="text-muted"><em>20 min ago</em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar14.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <a class="font-w600" href="javascript:void(0)">Scott Young</a>
                            <p class="my-1">Really nice!</p>
                            <a class="mr-1" href="javascript:void(0)">Like</a>
                            <a class="mr-1" href="javascript:void(0)">Reply</a>
                            <span class="text-muted"><em>42 min ago</em></span>
                            <div class="media mt-3">
                                <a class="mr-3" href="javascript:void(0)">
                                    <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar2.jpg" alt="">
                                </a>
                                <div class="media-body">
                                    <a class="font-w600" href="javascript:void(0)">Emma Cooper</a>
                                    <mark class="font-w600 text-success">Author</mark>
                                    <p class="my-1">Thanks so much!!</p>
                                    <a class="mr-1" href="javascript:void(0)">Like</a>
                                    <a class="mr-1" href="javascript:void(0)">Reply</a>
                                    <span class="text-muted"><em>20 min ago</em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="be_pages_ecom_store_product.php" method="POST" onsubmit="return false;">
                        <input type="text" class="form-control form-control-alt" placeholder="Write a comment..">
                    </form>
                </div>
                <!-- END Comments -->

                <!-- Reviews -->
                <div class="tab-pane pull-x font-size-sm" id="ecom-product-reviews" role="tabpanel">
                    <!-- Average Rating -->
                    <div class="block block-rounded bg-body-light">
                        <div class="block-content text-center">
                            <p class="text-warning mb-2">
                                <i class="fa fa-star fa-2x"></i>
                                <i class="fa fa-star fa-2x"></i>
                                <i class="fa fa-star fa-2x"></i>
                                <i class="fa fa-star fa-2x"></i>
                                <i class="fa fa-star fa-2x"></i>
                            </p>
                            <p>
                                <strong>5.0</strong>/5.0 out of <strong>5</strong> Ratings
                            </p>
                        </div>
                    </div>
                    <!-- END Average Rating -->

                    <!-- Ratings -->
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar11.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <span class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </span>
                            <a class="font-w600" href="javascript:void(0)">Thomas Riley</a>
                            <p class="my-1">Awesome Quality!</p>
                            <span class="text-muted"><em>2 hours ago</em></span>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar2.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <span class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </span>
                            <a class="font-w600" href="javascript:void(0)">Lori Moore</a>
                            <p class="my-1">So cool badges!</p>
                            <span class="text-muted"><em>5 hours ago</em></span>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar14.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <span class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </span>
                            <a class="font-w600" href="javascript:void(0)">Jose Mills</a>
                            <p class="my-1">They look great, thank you!</p>
                            <span class="text-muted"><em>15 hours ago</em></span>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar12.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <span class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </span>
                            <a class="font-w600" href="javascript:void(0)">Jack Estrada</a>
                            <p class="my-1">Badges for life!</p>
                            <span class="text-muted"><em>20 hours ago</em></span>
                        </div>
                    </div>
                    <div class="media push">
                        <a class="mr-3" href="javascript:void(0)">
                            <img class="img-avatar img-avatar32" src="assets/media/avatars/avatar2.jpg" alt="">
                        </a>
                        <div class="media-body">
                            <span class="text-warning">
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                                <i class="fa fa-star"></i>
                            </span>
                            <a class="font-w600" href="javascript:void(0)">Susan Day</a>
                            <p class="my-1">So good, keep it up!</p>
                            <span class="text-muted"><em>22 hours ago</em></span>
                        </div>
                    </div>
                    <!-- END Ratings -->
                </div>
                <!-- END Reviews -->
            </div>
        </div>
        <!-- END Extra Info Tabs -->
    </div>
</div>
@endsection