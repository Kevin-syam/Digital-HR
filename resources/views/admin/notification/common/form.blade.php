

<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="company_id" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select" id="company_id" name="company_id" required>
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Notification Title <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ ( isset($notificationDetail) ? $notificationDetail->title: old('title') )}}"
               autocomplete="off" placeholder="Enter Content Title">
    </div>



    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Notification Description <span style="color: red">*</span></label>
        <textarea class="form-control" name="description" id=""  rows="6">{{ ( isset($notificationDetail) ? $notificationDetail->description: old('description') )}}</textarea>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="is_active" class="form-label">Status</label>
        <select class="form-select" id="is_active" name="is_active" required>
            <option value="" {{isset($notificationDetail) ? '':'selected'}} >Select status</option>
            <option value="1" {{ isset($notificationDetail) && ($notificationDetail->is_active ) == 1 ? 'selected': old('is_active') }}>Active</option>
            <option value="0" {{ isset($notificationDetail) && ($notificationDetail->is_active ) == 0 ? 'selected': old('is_active') }}>Inactive</option>
        </select>
    </div>

    <div class="text-center mb-3 ">
        <button type="submit" class="btn btn-primary"> Send Notification</button>
    </div>

</div>







