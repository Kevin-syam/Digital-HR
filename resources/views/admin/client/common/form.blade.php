<div class="row">
    <div class="col-lg-6 mb-3">
        <label for="name" class="form-label"> Client Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" required value="{{ ( isset( $clientDetail) ?  $clientDetail->name: old('name') )}}"
               autocomplete="off" placeholder="Enter Client Name">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="email" class="form-label"> Client Email <span style="color: red">*</span></label>
        <input type="email" class="form-control" id="email" name="email" required value="{{ ( isset( $clientDetail) ?  $clientDetail->email: old('client_name') )}}"
               autocomplete="off" placeholder="Enter Client email" >
    </div>

    <div class="col-lg-6 mb-3">
        <label for="contact_no" class="form-label"> Client Contact Number <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="contact_no" name="contact_no" required value="{{ ( isset( $clientDetail) ?  $clientDetail->contact_no: old('contact_no') )}}"
               autocomplete="off" placeholder="Enter Contact Number" >
    </div>

    <div class="col-lg-6 mb-3">
        <label for="address" class="form-label"> Address  </label>
        <input type="text" class="form-control" id="address" name="address" required value="{{ ( isset( $clientDetail) ?  $clientDetail->address: old('address') )}}"
               autocomplete="off" placeholder="Enter Client address">
    </div>


    <div class="col-lg-6 mb-3">
        <label for="country" class="form-label"> Country <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="country" name="country" required value="{{ ( isset( $clientDetail) ?  $clientDetail->country: old('country') )}}"
               autocomplete="off" placeholder="Enter Country" >
    </div>

    <div class="col-lg-6 mb-3">
        <label for="avatar" class="form-label">Upload Client Profile <span style="color: red">*</span> </label>
        <input class="form-control" type="file" id="avatar" name="avatar" value="{{ isset($clientDetail) ? $clientDetail->avatar: old('avatar') }}" {{isset($clientDetail) ? '': 'required'}} >
        @if(isset($clientDetail) && $clientDetail->avatar)
            <img class="mt-3" src="{{asset(\App\Models\Client::UPLOAD_PATH.$clientDetail->avatar)}}"
                 alt="" width="200" style="object-fit: contain"
                 height="200">
        @endif
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($clientDetail)? 'edit-2':'plus'}}"></i> {{isset($clientDetail)? 'Update':'Create'}}</button>
    </div>
</div>







