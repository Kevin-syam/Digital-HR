
<div class="modal fade" id="addslider" tabindex="-1" aria-labelledby="addslider" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <strong>Query By:</strong> <p class="creator"> </p>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <strong>Status :</strong> <p class="status"> </p>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <strong>Branch:</strong> <p class="branch"> </p>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <strong>Department Support Requested From:</strong> <p class="department"></p>
                    </div>

                    <div class="col-lg-6 mb-4">
                        <strong>Department Support Requested To:</strong> <p class="requested"></p>
                    </div>
                </div>
                <div class="ticket-desc mb-4">
                    <strong>Description:</strong> <p class="description"> </p>
                </div>

                @can('update_query_status')
                    <form class="forms-sample" id="statusChange" action=""  method="post" >
                    @method('PUT')
                    @csrf
                    <div class="row">
                        <div class="col-ld-12">
                            <label for="" class="form-label ">Change Query Status</label>
                        </div>
                        <div class="col-lg-8 ps-lg-2">

                            <select class="form-select form-select-lg" name="status" id="changeStatus" required>
                                <option value="" >Select Status</option>
                                @foreach(\App\Models\Support::STATUS as $value)
                                    @if($value != 'pending')
                                        <option value="{{$value}}"> {{removeSpecialChars($value)}} </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-4 text-center pe-lg-2 text-lg-end">
                            <button type="submit" class="btn btn-primary submit">Update</button>
                        </div>
                    </div>
                </form>
                @endcan
            </div>
        </div>
    </div>
</div>
