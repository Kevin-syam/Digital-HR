
<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Add Client</h5>
            </div>
            <div class="modal-body">
                <div id="showErrorMessageResponse d-none">
                    <div class="alert alert-danger errorClient ">
                        <p class="errorClientMessage"></p>
                    </div>
                </div>

                <form class="forms-sample" id="client_form" action="{{route('admin.clients.ajax-store')}}" enctype="multipart/form-data"  method="post" >
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="clientName" class="form-label"> Client Name <span style="color: red">*</span></label>
                            <input type="text" class="form-control" id="clientName" name="name" required value=""
                                   autocomplete="off" placeholder="Enter Client Name">
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="email" class="form-label"> Client Email <span style="color: red">*</span></label>
                            <input type="email" class="form-control" id="email" name="email" required value=""
                                   autocomplete="off" placeholder="Enter Client email" >
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="contact_no" class="form-label"> Client Contact Number <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" id="contact_no" name="contact_no" required value=""
                                   autocomplete="off" placeholder="Enter Contact Number" >
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="address" class="form-label"> Address  </label>
                            <input type="text" class="form-control" id="address" name="address"  value=""
                                   autocomplete="off" placeholder="Enter Client address" >
                        </div>


                        <div class="col-lg-6 mb-3">
                            <label for="country" class="form-label"> Country <span style="color: red">*</span> </label>
                            <input type="text" class="form-control" id="country" name="country" required value=""
                                   autocomplete="off" placeholder="Enter Country" >
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="avatar" class="form-label">Upload Client Profile <span style="color: red">*</span> </label>
                            <input class="form-control" type="file" id="avatar"  name="avatar" value="" >

                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary submit"><i class="link-icon" data-feather="plus"></i> Create</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>





