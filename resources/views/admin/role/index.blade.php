@extends('layouts.admin')

@section('content')
<div class="block m-3">
    <div class="block-header">
        <h3 class="block-title">Roles</h3>
    </div>
    <div class="block-content block-content-full">
        <form action="be_forms_elements.php" method="POST" onsubmit="return false;">
            <div class="row">
                <div class="col-lg-4">
                    <p class="font-size-sm text-muted">
                        Enter the name of roles.
                    </p>
                </div>
                <div class="col-lg-8 col-xl-5">
                    <div class="form-group">
                        <label for="example-text-input-alt">Role name</label>
                        <input type="text" class="form-control form-control-alt" id="example-text-input-alt" name="example-text-input-alt" placeholder="Text Input">
                    </div>
                    
                </div>
            </div>
        </form>
    </div>
</div>
@endsection