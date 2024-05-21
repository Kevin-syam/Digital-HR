<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="client_id" class="form-label">Client <span style="color: red">*</span> </label>
        <div class="d-flex align-items-center">
            <select class="form-select" id="client_id" name="client_id"  >
                <option value="" {{isset($projectDetail) ? '' : 'selected'}}  disabled>Select Client</option>
                @foreach($clientLists as $key => $value)
                    <option value="{{$value->id}}" {{ (isset($projectDetail) && ($projectDetail->client_id ) == $value->id) || ( old('client_id') == $value->id) ? 'selected': '' }}>
                        {{$value->name}}
                    </option>
                @endforeach
            </select>

            @if(!isset($projectDetail))
                <a class="btn btn-xs btn-primary add_client ms-2"
                   data-bs-toggle="modal"
                   data-bs-target="#addslider">
                    Add Client
                </a>
            @endif
        </div>
    </div>


    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Project Name <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="name" name="name" required value="{{ ( isset( $projectDetail) ?  $projectDetail->name: old('name') )}}"
               autocomplete="off" placeholder="Enter Project Name">
    </div>

    @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
        <div class="col-lg-6 mb-3">
            <label for="start_date" class="form-label"> Project Start Date <span style="color: red">*</span> </label>
            <input type="text" id="nepali_startDate"
                   name="start_date"
                   value="{{ ( isset( $projectDetail) ?  $projectDetail->start_date: old('start_date') )}}"
                   placeholder="yyyy-mm-dd"
                   class="form-control startDate"/>
        </div>

        <div class="col-lg-6 mb-3">
            <label for="deadline" class="form-label"> Project Deadline <span style="color: red">*</span> </label>
            <input type="text"
                   id="nepali_deadline"
                   name="deadline"
                   value="{{ ( isset( $projectDetail) ?  $projectDetail->deadline: old('deadline') )}}"
                   placeholder="yyyy-mm-dd"
                   class="form-control deadline"/>
        </div>
    @else
        <div class="col-lg-6 mb-3">
            <label for="start_date" class="form-label"> Project Start Date <span style="color: red">*</span> </label>
            <input type="date"
                   class="form-control"
                   id="start_date"
                   name="start_date"
                   required
                   value="{{ ( isset( $projectDetail) ?  $projectDetail->start_date: old('start_date') )}}"
                   autocomplete="off" >
        </div>

        <div class="col-lg-6 mb-3">
            <label for="deadline" class="form-label"> Project Deadline <span style="color: red">*</span> </label>
            <input type="date"
                   class="form-control"
                   id="deadline"
                   name="deadline"
                   required
                   value="{{ ( isset( $projectDetail) ?  $projectDetail->deadline: old('deadline') )}}"
                   autocomplete="off" >
        </div>
    @endif

    <div class="col-lg-6 mb-3">
        <label for="status" class="form-label">Project Status </label>
        <select class="form-select" id="status" name="status"  >
            <option value="" {{isset($projectDetail) ? '' : 'selected'}}  disabled>Select Project Status</option>
            @foreach(\App\Models\Project::STATUS as $value)
                <option value="{{$value}}" {{ (isset($projectDetail) && ($projectDetail->status ) == $value) || (old('status') == $value) ? 'selected': '' }}>
                    {{\App\Helpers\PMHelper::STATUS[$value]}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="cost" class="form-label"> Project Cost <span style="color: red">*</span> </label>
        <input type="number" class="form-control" id="cost" name="cost" required value="{{ ( isset( $projectDetail) ?  $projectDetail->cost: old('cost') )}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-6 mb-3">
        <label for="priority" class="form-label">Priority</label>
        <select class="form-select" id="priority" name="priority"  >
            <option value="" {{isset($projectDetail) ? '' : 'selected'}}  disabled>Select Priority</option>
            @foreach(\App\Models\Project::PRIORITY as $value)
                <option value="{{$value}}" {{ (isset($projectDetail) && ($projectDetail->priority ) == $value) || ( old('priority') == $value) ? 'selected': '' }}>
                    {{ucfirst($value)}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="estimated_hours" class="form-label"> Estimated Hours </label>
        <input type="number" step="0.5" class="form-control" id="estimated_hours" name="estimated_hours"  value="{{ ( isset( $projectDetail) ?  $projectDetail->estimated_hours: old('estimated_hours') )}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-12 mb-3">
        <div class="row ">
            <div class="col-lg-6 mb-4">
                <label for="upload" class="form-label">Upload Cover Picture <span style="color: red">*</span></label>
                <input class="form-control" type="file" id="upload"  accept=",.jpg,.jpeg,.png" name="cover_pic" >
            </div>

            <div class="col-lg-6 mb-4">
            @if(isset($projectDetail) && $projectDetail->cover_pic)
                <img src="{{asset(\App\Models\Project::UPLOAD_PATH.$projectDetail->cover_pic)}}"
                      alt=""  style="object-fit: contain" class="mt-3 w-25 rounded"
                >
            @endif
            </div>
        </div>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="teamLeader" class="form-label">Project Leader <span style="color: red">*</span></label>
        <br>
        <select class="col-md-12 from-select" id="projectLead" name="project_leader[]" multiple="multiple" required>
            @foreach($employees as $key => $value)
                <option value="{{$value->id}}" {{ isset($projectDetail) && in_array($value->id,$leaderId)  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="employee" class="form-label">Assign Member <span style="color: red">*</span></label>
        <br>
        <select class="col-md-12 from-select" id="member" name="assigned_member[]" multiple="multiple" required>
            @foreach($employees as $key => $value)
                <option value="{{$value->id}}" {{ isset($projectDetail) && in_array($value->id,$memberId)  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
            @endforeach
        </select>
    </div>

    @if(isset($projectDetail))
        <div class="mb-3 col-12">
            <label for="" class="form-label">Uploaded Files And Images </label>
            @if(count($files) < 1 && count($images) < 1)
                <div class="row">
                    <p class="text-muted">No project file uploaded</p>
                </div>
            @endif
            <div class="row mb-3">
                @forelse($images as $key => $imageData)
                    <div class="col-lg-3">
                        <div class="uploaded-image">
                            <img class="w-100" style=""
                                 src="{{ asset(\App\Models\Attachment::UPLOAD_PATH.$imageData->attachment) }}"
                                 alt="document images">
                            <a class="documentDelete" data-href="{{route('admin.attachment.delete',$imageData->id)}}">
                                <i class="link-icon remove-image" data-feather="x"></i>
                            </a>
                        </div>
                    </div>
                @empty

                @endforelse
            </div>
            <div class="row">
                @forelse($files as $key => $fileData)
                    <div class="uploaded-files">
                        <div class="row align-items-center">
                            <div class="col-lg-1">
                                <div class="file-icon">
                                    <i class="link-icon" data-feather="file-text"></i>
                                </div>
                            </div>
                            <div class="col-lg-10">
                                <a target="_blank" href="{{asset(\App\Models\Attachment::UPLOAD_PATH.$fileData->attachment)}}">
                                    {{asset(\App\Models\Attachment::UPLOAD_PATH.$fileData->attachment)}}
                                </a>
                            </div>

                            <div class="col-lg-1">
                                <a class="documentDelete" data-href="{{route('admin.attachment.delete',$fileData->id)}}">
                                    <i class="link-icon remove-files" data-feather="x"></i>
                                </a>
                            </div>

                        </div>

                    </div>
                @empty

                @endforelse
            </div>

    </div>
    @endif

    <div class="mb-3 col-12">
        <div>
            <input id="image-uploadify" type="file"  name="attachments[]"
                   accept=".pdf,.jpg,.jpeg,.png,.docx,.doc,.xls,.txt,.zip"  multiple />
        </div>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="description" class="form-label">Description<span style="color: red">*</span></label>
        <textarea class="form-control" name="description" id="tinymceExample" rows="4">{{ ( isset($projectDetail) ? $projectDetail->description: old('description') )}}</textarea>
    </div>

    <div class="col-lg-12">
        <button type="submit" class="btn btn-primary"><i class="link-icon" data-feather="{{isset($projectDetail)? 'edit-2':'plus'}}"></i> {{isset($projectDetail)? 'Update':'Create'}} Project</button>
    </div>

</div>







