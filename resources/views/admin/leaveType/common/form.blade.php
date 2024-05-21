

<div class="row">

    <div class="col-lg-4 mb-3">
        <label for="name" class="form-label"> Leave Type Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" value="{{ ( isset($leaveDetail) ? $leaveDetail->name: old('name') )}}" required autocomplete="off" placeholder="Enter Leave Type">
    </div>

    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Is Paid Leave <span style="color: red">*</span></label>
        <select class="form-select" id="leave_paid" required name="leave_paid">
            <option value="" {{ isset($leaveDetail) ? '':'selected'}} disabled></option>
            <option value="1" {{ isset($leaveDetail) && $leaveDetail->leave_allocated > 0  ? 'selected':''}} >Yes</option>
            <option value="0"  {{ isset($leaveDetail) && is_null($leaveDetail->leave_allocated)      ? 'selected':'' }}>No</option>
        </select>
    </div>

    <div class="col-lg-4 mb-3 leaveAllocated " >
        <label for="leave_allocated" class="form-label">Leave Allocated Days <span style="color: red">*</span></label>
        <input type="number" min="1" class="form-control" id="leave_allocated"  name="leave_allocated" value="{{ isset($leaveDetail)? $leaveDetail->leave_allocated: old('leave_allocated') }}" autocomplete="off" placeholder="">
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($leaveDetail)? 'Update':'Create'}} Leave Type</button>
    </div>
</div>


