
<style>
    .img-wrap {
        position: relative;
        display: inline-block;
        font-size: 0;
    }
    .img-wrap .close {
        position: absolute;
        top: 2px;
        right: 2px;
        z-index: 100;
        background-color: #FFF;
        padding: 5px 2px 2px;
        color: #000;
        font-weight: bold;
        cursor: pointer;
        opacity: .5;
        text-align: center;
        font-size: 30px;
        line-height: 20px;
        border-radius: 50%;
    }
    .img-wrap:hover .close {
        opacity: 1;
    }
</style>

<div class="row">

    <div class="col-lg-6 mb-3">
        <label for="company_id" class="form-label">Company Name <span style="color: red">*</span></label>
        <select class="form-select"  id="company_id" name="company_id" required>
            <option selected value="{{ isset($companyDetail) ? $companyDetail->id : '' }}" >{{ isset($companyDetail) ? $companyDetail->name : ''}}</option>
        </select>
    </div>

    <div class="col-lg-6 mb-3">
        <label for="title" class="form-label"> Meeting title <span style="color: red">*</span></label>
        <input type="text" class="form-control" id="title" name="title" required value="{{ ( isset( $teamMeetingDetail) ?  $teamMeetingDetail->title: old('title') )}}"
               autocomplete="off" placeholder="Enter Content Title">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="meeting_date" class="form-label"> Meeting Date <span style="color: red">*</span></label>
        <input class="form-control"
               name="meeting_date"
               value="{{(isset($teamMeetingDetail) ? $teamMeetingDetail->meeting_date: old('meeting_date'))}}"
               required
               autocomplete="off"
               @if(\App\Helpers\AppHelper::ifDateInBsEnabled())
                   type="text"
                   id="meetingDate"
                   placeholder="yyyy/mm/dd"
               @else
                type="date"
            @endif
        />
    </div>

    <div class="col-lg-6 mb-3">
        <label for="meeting_start_time" class="form-label"> Meeting Start Time <span style="color: red">*</span> </label>
        <input type="time" class="form-control" id="meeting_start_time" name="meeting_start_time" required value="{{ ( isset( $teamMeetingDetail) ?  $teamMeetingDetail->meeting_start_time: old('meeting_start_time') )}}"
               autocomplete="off" >
    </div>

    <div class="col-lg-6 mb-3">
        <label for="venue" class="form-label"> Meeting Venue <span style="color: red">*</span> </label>
        <input type="text" class="form-control" id="venue" name="venue" required value="{{ ( isset( $teamMeetingDetail) ?  $teamMeetingDetail->venue: old('venue') )}}"
               autocomplete="off" placeholder="Enter venue">
    </div>

    <div class="col-lg-6 mb-3">
        <label for="description" class="form-label">Meeting Description <span style="color: red">*</span></label>
        <textarea class="form-control" minlength="10" name="description" id=""  rows="6">{!! ( isset( $teamMeetingDetail) ?  $teamMeetingDetail->description: old('description') ) !!} </textarea>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="employee" class="form-label">Meeting participator <span style="color: red">*</span></label>
        <br>
        <select class=" col-md-12 from-select" id="team_meeting" name="participator[][meeting_participator_id]" multiple="multiple" required>
            @foreach($userDetail as $key => $value)
                <option value="{{$value->id}}" {{ isset( $teamMeetingDetail) && in_array($value->id,$participatorIds)  ? 'selected' : '' }}  >{{ucfirst($value->name)}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-lg-12 mb-3">
        <label for="image" class="form-label">Upload Image</label>
        <input class="form-control" type="file" accept="image/png, image/jpeg,image/jpg, image/svg,"   id="image" name="image" />
        <small>*Image is recommended to be in landscape form</small>

        @if(isset($teamMeetingDetail) && $teamMeetingDetail->image)
            <div class="img-wrap mt-3" style="object-fit: contain">
                <span class="close removeImage" data-href="{{route('admin.team-meetings.remove-image',$teamMeetingDetail->id)}}">&times;</span>
                <img   src="{{asset(\App\Models\TeamMeeting::UPLOAD_PATH.$teamMeetingDetail->image)}}"
                     alt="" width="200"
                     height="200">
            </div>
        @endif

    </div>

    <div class="text-center mb-3">
        <button type="submit" class="btn btn-primary">{{isset($teamMeetingDetail) ? 'update And' : 'Create And'}} Send </button>
    </div>

</div>







