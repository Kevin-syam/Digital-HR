
<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
            </div>
            <div class="modal-body">
                 <form class="forms-sample" id="updateTadaStatus" action=""  method="post" >
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-ld-12">
                            <label for="" class="form-label ">Status</label>
                        </div>

                        <div class="col-lg-12 ps-lg-2 mb-3">
                            <select class="form-select form-select-lg" name="status" id="tada_status" required>
                                <option value="pending" > Pending </option>
                                <option value="accepted"> Accepted </option>
                                <option value="rejected"> Rejected </option>
                            </select>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="description" class="form-label">Remark</label>
                            <textarea class="form-control remark" required name="remark" id="reason"  rows="4"></textarea>
                        </div>

                        @can('edit_tada')
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-primary update">Update</button>
                            </div>
                        @endcan
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
