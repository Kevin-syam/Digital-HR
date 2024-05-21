

<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="name" class="form-label"> Role Name <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="name" required name="name" value="{{ ( isset($roleDetail) ? $roleDetail->name: '' )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Authorize Backend Login</label>
        <select class="form-select" id="exampleFormControlSelect1" name="backend_login_authorize">
            <option value="" {{isset($roleDetail) ? '':'selected'}} >Select status</option>
            <option value="1" {{ isset($roleDetail) && ($roleDetail->backend_login_authorize ) == 1 ? 'selected': old('backend_login_authorize') }}>Yes</option>
            <option value="0" {{ isset($roleDetail) && ($roleDetail->backend_login_authorize ) == 0 ? 'selected': old('backend_login_authorize') }}>No</option>
        </select>
    </div>


    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value=""  disabled>Select status</option>
            <option value="1" {{ isset($roleDetail) && ($roleDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($roleDetail) && ($roleDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>



    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($roleDetail)? 'Update':'Create'}} Role</button>
    </div>
</div>
