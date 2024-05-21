

<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="company_id" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="company_id" name="company_id" required>
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Content Title <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ ( isset($companyContentDetail) ? $companyContentDetail->title: old('title') )}}" autocomplete="off" placeholder="Enter Content Title">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="content_type" class="form-label">Content Type <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="content_type" required>
            <option value=""  {{isset($companyContentDetail) ? '':'selected'}}>Select Content Type</option>
            @foreach(\App\Models\CompanyContentManagement::CONTENT_TYPE as $value)
                <option value="{{ $value }}" {{ (isset($companyContentDetail) && ($companyContentDetail->content_type ) == $value) ? 'selected':old('category') }} >{{ removeSpecialChars($value) }}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="exampleFormControlSelect1" class="form-label">Status <span style="color: red">*</span></label>
        <select class="form-select" id="exampleFormControlSelect1" name="is_active" required>
            <option value="" {{isset($companyContentDetail) ? '':'selected'}} >Select status</option>
            <option value="1" {{ isset($companyContentDetail) && ($companyContentDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($companyContentDetail) && ($companyContentDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Description <span style="color: red">*</span></label>
        <textarea class="form-control" name="description"  id="tinymceExample" rows="6">{{ ( isset($companyContentDetail) ? $companyContentDetail->description: old('description') )}}</textarea>
    </div>



    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> {{isset($companyContentDetail)? 'Update':'Create'}} Content</button>
    </div>
</div>







