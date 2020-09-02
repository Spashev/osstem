@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="block">
        <div class="block-header">
            <h3 class="block-title">Manager</h3>
            <div class="block-options">
                <div class="block-options-item">
                    <code>create</code>
                </div>
            </div>
        </div>
        <div class="block-content">
            <table class="table table-bordered table-vcenter">
                <thead>
                    <tr>
                        <th class="text-center" style="width: 50px;">#</th>
                        <th>Name</th>
                        <th class="d-none d-sm-table-cell" style="width: 15%;">In_charge</th>
                        <th class="text-center" style="width: 100px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($managers as $manager)
                        <tr>
                        <th class="text-center" scope="row">{{$manager->id}}</th>
                            <td class="font-w600 font-size-sm">
                                <a href="be_pages_generic_profile.php">{{$manager->name}}</a>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                <span class="badge badge-info">{{$manager->in_charge}}</span>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="{{route('admin.manager.edit', $manager->id)}}" class="btn btn-sm btn-primary js-tooltip-enabled" data-toggle="tooltip" title="edit manager" data-original-title="Edit Client">
                                        <i class="fa fa-fw fa-pencil-alt text-white"></i>
                                    </a>
                                    <a href="{{route('admin.manager.delete', $manager->id)}}"class="btn btn-sm btn-danger js-tooltip-enabled" data-toggle="tooltip" title="delete manager" data-original-title="Remove Client">
                                        <i class="fa fa-fw fa-times text-white"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="p-2">
            {{$managers->links()}}
        </div>
    </div>
</div>
@endsection
