

<div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form class="forms-sample" id="updateLeaveStatus" action=""  method="post" >
                        @csrf
                        @method('put')
                        <div class="row">
                            <label for="exampleFormControlSelect1" class="form-label">Status </label>
                            <div class="col-lg-12 mb-3">
                                <select class="form-select" id="status" name="status">
                                    <option value="approved" >Approve</option>
                                    <option value="rejected" >Reject</option>
                                </select>
                            </div>

                            <label for="exampleFormControlSelect1" class="form-label">Admin Remark</label>
                            <div class="col-lg-12 mb-3">
                                <textarea class="form-select" id="remark" minlength="10" name="admin_remark" rows="3" value="">
                                </textarea>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" id="submit-btn" class="btn btn-primary btn-xs"> submit </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>





