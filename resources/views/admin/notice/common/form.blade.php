

<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="company_id" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select"  id="company_id" name="company_id" required>
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Notice title <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ ( isset($noticeDetail) ? $noticeDetail->title: old('title') )}}"
               autocomplete="off" placeholder="Enter Content Title">
    </div>

    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Notice Description <span style="color: red">*</span></label>
        <textarea class="form-control" minlength="10"  name="description" id="" value=""  rows="6">{!! ( isset($noticeDetail) ? $noticeDetail->description: old('description') ) !!} </textarea>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="employee" class="form-label">Notice Receiver <span style="color: red">*</span></label>
        <br>
        <select class="col-md-12 from-select" id="notice" name="receiver[][notice_receiver_id]" multiple="multiple" required>
            @foreach($userDetail as $key => $value)
                <option value="{{$value->id}}" {{ isset($noticeDetail) && in_array($value->id,$receiverUserIds)  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
            @endforeach
        </select>

        <input class="mt-3" type="checkbox" id="checkbox" > All Employees
    </div>

    <div class="col-lg-6 mb-3">
        <label for="is_active" class="form-label">Status <span style="color: red">*</span></label>
        <select class="form-select" id="is_active" name="is_active" required>
            <option value="" {{isset($noticeDetail) || old('is_active') ? '':'selected'}} >Select status</option>
            <option value="1" {{ isset($noticeDetail) && ($noticeDetail->is_active  || old('is_active') )== 1 ? 'selected': '' }}>Active</option>
            <option value="0" {{ isset($noticeDetail) && ($noticeDetail->is_active || old('is_active') ) == 0 ? 'selected': '' }}>Inactive</option>
        </select>
    </div>

    <div class="text-center mb-3 ">
        <button type="submit" class="btn btn-primary"> Send Notice</button>
    </div>

</div>







