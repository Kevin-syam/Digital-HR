

<div class="row">
    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="company_id" required>
            <option selected value="{{ ($companyDetail) ? $companyDetail->id:'' }}" >{{  ($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>


    <div class="col-lg-6 mb-3">
        <label for="opening_time" class="form-label"> Opening Time <span style="color: red">*</span></label>
        <input type="time" class="form-control" id="opening_time" name="opening_time" required value="{{ ( isset($officeTime) ? convertTimeFormat($officeTime->opening_time): old('opening_time') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="closing_time" class="form-label"> Closing Time <span style="color: red">*</span></label>
        <input type="time" class="form-control" id="closing_time" name="closing_time" required value="{{ ( isset($officeTime) ? convertTimeFormat($officeTime->closing_time): old('closing_time') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="shift" class="form-label">Shift <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="shift" required>
            <option value="" {{isset($officeTime) ? '': 'selected'}} disabled>Select Shift</option>
           @foreach($shift as $value)
                <option value="{{ $value }}" {{ (isset($officeTime) && ($officeTime->shift ) == $value) ? 'selected':old('shift') }} >{{ ucfirst($value) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 mb-3">
        <label for="shift" class="form-label">Category</label>
        <select class="form-select" id="exampleFormControlSelect1" name="category">
            <option value=""  disabled>Select Category</option>
            @foreach($category as $value)
                <option value="{{ $value }}" {{ (isset($officeTime) && ($officeTime->category ) == $value) ? 'selected':old('category') }} >{{ removeSpecialChars($value) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-4 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status</label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active">
            <option value="" {{isset($officeTime) ? '': 'selected'}} disabled>Select status</option>
            <option value="1" @selected( old('is_active', isset($officeTime) && $officeTime->is_active ) === 1)>Active</option>
            <option value="0" @selected( old('is_active', isset($officeTime) && $officeTime->is_active ) === 0)>Inactive</option>
        </select>
    </div>
    <div class="col-lg-4 mb-3">
        <label for="holiday" class="form-label">Weekly Holiday Count</label>
        <input type="number" min="0" class="form-control" id="holiday_count" name="holiday_count" value="{{ ( isset($officeTime) ? $officeTime->holiday_count: old('holiday_count') )}}" autocomplete="off" placeholder="">
    </div>

    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="tinymceExample" rows="10">{{ ( isset($officeTime) ? $officeTime->description: old('description') )}}</textarea>
    </div>



    <div class="text-end">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($officeTime)? 'edit-2':'plus'}}"></i> {{isset($officeTime)? 'Update':'Create'}} Office Time</button>
    </div>
</div>
