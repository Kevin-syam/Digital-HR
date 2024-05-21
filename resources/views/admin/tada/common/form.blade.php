<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="employee_id" class="form-label">Employee <span style="color: red">*</span></label>
        <select class="form-select" id="employee_id" name="employee_id"  >
            <option value="" {{isset($tadaDetail) ? '' : 'selected'}}  disabled>Select Employee</option>
            @foreach($employee as $key => $value)
                <option value="{{$value->id}}" {{ (isset($tadaDetail) && ($tadaDetail->employee_id ) == $value->id) || ( old('employee_id') == $value->id) ? 'selected': '' }}>
                    {{ucfirst($value->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Title <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ ( isset($tadaDetail) ?  $tadaDetail->title: old('title') )}}"
               autocomplete="off" placeholder="Enter TADA Title">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="expense" class="form-label"> Total Expense <span style="color: red">*</span> </label>
        <input type="number" min="0" class="form-control" id="total_expense" name="total_expense" required value="{{ ( isset( $tadaDetail) ?  $tadaDetail->total_expense: old('total_expense') )}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Description</label>
        <textarea class="form-control" name="description" id="tinymceExample" rows="4">{{ ( isset($tadaDetail) ? $tadaDetail->description: old('description') )}}</textarea>
    </div>

    @if(isset($attachments))
        <div class="mb-3 col-12" >
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="">Uploaded Attachment </h6>
                </div>
                <div class="card-body">
                    @if(count($attachments) < 1 )
                        <div class="row">
                            <p class="text-muted">No Attachment file</p>
                        </div>
                    @endif
                    <div class="row mb-4">
                        @forelse($attachments as $key => $data)
                             @if(!in_array(pathinfo(asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment), PATHINFO_EXTENSION),['docx','pdf','doc','xls','txt'])  )
                                <div class="col-lg-3 mb-4">
                                    <div class="uploaded-image">
                                        <img class="w-100" style=""
                                             src="{{ asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment) }}"
                                             alt="document images">
                                        <a class="delete" data-title="attachment image" data-href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                            <i class="link-icon remove-image" data-feather="x"></i>
                                        </a>
                                    </div>
                                </div>
                            @else
                                <div class="uploaded-files">
                                    <div class="row align-items-center">
                                        <div class="col-lg-1">
                                            <div class="file-icon">
                                                <i class="link-icon" data-feather="file-text"></i>
                                            </div>
                                        </div>
                                        <div class="col-lg-10">
                                            <a target="_blank" href="{{asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}">
                                                {{asset(\App\Models\TadaAttachment::ATTACHMENT_UPLOAD_PATH.$data->attachment)}}
                                            </a>
                                        </div>

                                        <div class="col-lg-1">
                                            <a class="delete" data-title="attachment file" data-href="{{route('admin.tadas.attachment-delete',$data->id)}}">
                                                <i class="link-icon remove-files" data-feather="x"></i>
                                            </a>
                                        </div>

                                    </div>

                                </div>
                            @endif

                        @empty

                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="mb-3 col-12">
        <h6 class="mb-2"> Attachments <span style="color: red">*</span></h6>
        <div>
            <input id="image-uploadify" type="file"  name="attachments[]"
                   accept=".pdf,.jpg,.jpeg,.png,.docx,.doc,.xls,.txt,.zip"  multiple />
        </div>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary ">
            <i class="link-icon" data-feather="{{isset($tadaDetail)? 'edit-2':'plus'}}"></i>
            {{isset($tadaDetail)? 'Update':'Create'}} TADA
        </button>
    </div>
</div>







