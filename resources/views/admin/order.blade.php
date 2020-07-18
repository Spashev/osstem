@extends('layouts.admin')

@section('title', 'Orders')

@section('content')
<div class="content content-boxed">
    <div class="block">
        <div class="block-content block-content-full">
        <div class="block-content">
            <table class="table table-hover table-vcenter">
                <thead>
                    <tr>
                        <th style="width: 30px;"></th>
                        <th>Name</th>
                        <th style="width: 15%;">Description</th>
                        <th class="d-none d-sm-table-cell" style="width: 20%;">Date</th>
                    </tr>
                </thead>
                @foreach($orders as $order)
                    <tbody data-toggle="collapse" href="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                        <tr>
                            <td class="text-center">
                                <i class="fa fa-angle-right text-muted"></i>
                            </td>
                            <td class="font-w600 font-size-sm">
                                <div class="py-1">
                                    <a href="be_pages_generic_profile.php">{{ $order->title }}</a>
                                </div>
                            </td>
                            <td>
                            <span class="badge badge-warning">{{ $order->description }}</span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                            <em class="font-size-sm text-muted">{{ $order->created_at }}</em>
                            </td>
                        </tr>
                    </tbody>
                    <tbody class="collapse" id="collapseExample">
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $224,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $957,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                        <tr>
                            <td class="text-center"></td>
                            <td class="font-w600 font-size-sm">+ $625,00</td>
                            <td colspan="2">
                                <i class="fab fa-paypal"></i>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
