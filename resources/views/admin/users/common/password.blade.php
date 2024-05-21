

<div class="modal fade" id="statusUpdate" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel">Add Branch</h5>
            </div>
            <div class="modal-body">
                <div class="container">
                    <form class="forms-sample" id="changePassword" action=""  method="post" >
                        @csrf
                        <div class="row">

                            <div class="col-lg-6 mb-3">
                                <label for="password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="NewPassword" name="new_password" value="" autocomplete="off" placeholder="Enter new password" required>
                            </div>

                            <div class="col-lg-6 mb-3">
                                <label for="password" class="form-label">Confirm Password</label>
                                <input type="password" class="form-control" id="confirmPassword" name="confirm_password" value="" autocomplete="off" placeholder="confirm password" required>
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





