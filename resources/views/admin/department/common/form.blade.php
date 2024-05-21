

<div class="row">
    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="company_id">
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Branch <span style="color: red">*</span> </label>
        <select class="form-select" id="exampleFormControlSelect1" name="branch_id" required >
            <option value="" {{!isset($departmentsDetail) ? 'selected': ''}}  disabled >Select Branch</option>
            @if($companyDetail)
                @foreach($companyDetail->branches()->get() as $key => $branch)
                    <option value="{{ $branch->id }}" @selected( old('branch_id', isset($departmentsDetail) && $departmentsDetail->branch_id ) == $branch->id)>{{ucfirst($branch->name)}}</option>
    {{--                <option value="{{ $branch->id }}" {{ (isset($departmentsDetail) && $branch->id == $departmentsDetail->branch_id) ?'selected':'' }} > {{ucfirst($branch->name)}} </option>--}}
                @endforeach
            @endif
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="name" class="form-label"> Department Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="dept_name" required name="dept_name" value="{{ ( isset($departmentsDetail) ? $departmentsDetail->dept_name: '' )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Department Head</label>
        <select class="form-select" id="exampleFormControlSelect1" name="dept_head_id">
            <option value="" {{!isset($departmentsDetail) ? 'selected': ''}}  disabled >Select Department Head</option>
            @foreach($users as $key => $user)
                <option value="{{ $user->id }}" @selected( old('dept_head_id', isset($departmentsDetail) && $departmentsDetail->dept_head_id ) == $user->id)>{{ucfirst($user->name)}}</option>

{{--                <option value="{{$user->id}}" {{ (isset($departmentsDetail) && $user->id == $departmentsDetail->dept_head_id ) ?'selected':'' }} > {{ucfirst($user->name)}} </option>--}}
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="address" class="form-label"> Address <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="address" required name="address" value="{{ isset($departmentsDetail)? $departmentsDetail->address: old('address') }}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="number" class="form-label">Phone No <span style="color: red">*</span></label>
        <input type="number" class="form-control" id="phone" required name="phone" value="{{ isset($departmentsDetail)? $departmentsDetail->phone: old('phone') }}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value="" {{!isset($departmentsDetail) ? 'selected': ''}} disabled>Select status</option>
            <option value="1" {{ isset($departmentsDetail) && ($departmentsDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($departmentsDetail) && ($departmentsDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>


    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($departmentsDetail)? 'Update':'Create'}} Department</button>
    </div>
</div>
