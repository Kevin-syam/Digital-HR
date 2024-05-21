

<div class="row">
    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="company_id" required>
            <option selected value="{{ $companyDetail->id }}" >{{$companyDetail->name}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Branch <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="branch_id" required>
            <option value="" {{isset($routerDetail) ? '': 'selected'}}  disabled >Select Branch</option>
            @foreach($companyDetail->branches()->get() as $key => $branch)
                <option value="{{ $branch->id }}" {{ (isset($routerDetail) && ($routerDetail->branch_id ) === $branch->id) ? 'selected': old('branch_id') }}> {{ucfirst($branch->name)}}</option>
            @endforeach
        </select>
    </div>


    <div class="col-lg-6 mb-3">
        <label for="router_ssid" class="form-label"> Router SSID <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="router_ssid" required name="router_ssid" value="{{ ( isset($routerDetail) ? ($routerDetail->router_ssid): old('router_ssid') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value="" {{isset($routerDetail) ? '': 'selected'}}  disabled>Select status</option>
            <option value="1" {{ isset($routerDetail) && ($routerDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($routerDetail) && ($routerDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>


    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($routerDetail)? 'edit-2':'plus'}}"></i> {{isset($routerDetail)? 'Update':'Add'}} Router</button>
    </div>
</div>
