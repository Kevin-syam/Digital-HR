

<div class="row">
    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Department <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="dept_id" required>

            <option value=""  disabled >Select Department</option>
            @foreach($departmentDetail as $key => $department)
                <option value="{{ $department->id }}" {{ (isset($postDetail) && $department->id === $postDetail->dept_id )? 'selected':''}}>
                    {{ucfirst($department->dept_name)}}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 mb-3">
        <label for="name" class="form-label"> Post Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="post_name" required name="post_name" value="{{ ( isset($postDetail) ? $postDetail->post_name: '' )}}" autocomplete="off" placeholder="">
    </div>


    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value=""  disabled>Select status</option>
            <option value="1" {{ isset($postDetail) && ($postDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($postDetail) && ($postDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>


    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($postDetail)? 'Update':'Create'}} Post</button>
    </div>
</div>
