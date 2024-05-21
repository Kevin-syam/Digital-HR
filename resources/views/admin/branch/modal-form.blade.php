

<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
            </div>
            <div class="modal-body">

                    <form class="forms-sample" id="branch_form" action="{{route('admin.branch.store')}}"  method="post" >
                    @csrf
                    <div class="row">

                        <input type="hidden" class="form-control" id="company_id" readonly name="company_id" value="" autocomplete="off" placeholder="">

                        <div class="col-lg-6 mb-3" >
                            <label for="name" class="form-label"> Branch Name <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="" autocomplete="off" placeholder="">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="address" class="form-label"> Address <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" id="address" required value="" name="address" autocomplete="off" placeholder="">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Branch Head</label>
                            <select class="form-select branch_head" id="branch_head" name="branch_head_id">
                                <option  value=""  >Select Branch Head</option>
                            </select>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="phone" class="form-label"> Phone No <span style="color: red">*</span></label>
                            <input type="number" class="form-control mobile" required  id="phone" name="phone" value="" autocomplete="off" placeholder="">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="branch_location_latitude" class="form-label"> Branch location latitude <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="branch_location_latitude" name="branch_location_latitude" value="" autocomplete="off" placeholder="Enter Branch Location Latitude">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="branch_location_longitude" class="form-label"> Branch location longitude <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="branch_location_longitude" name="branch_location_longitude" value="" autocomplete="off" placeholder="Enter Branch Location Longitude">
                        </div>


                        <div class="col-lg-6 mb-3">
                            <label for="exampleFormControlSelect1" class="form-label">Status</label>
                            <select class="form-select" id="status" name="is_active">
                                <option value=""  disabled>Select status</option>
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>

                    <div class="text-center">
                        <input type="hidden" name="_method" value="post" id="update" >
                        <button type="submit" id="submit-btn" class="btn btn-primary"><i class="link-icon" data-feather="plus"></i> Create </button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>





